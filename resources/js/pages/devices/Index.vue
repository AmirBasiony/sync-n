<script setup lang="ts">
import { ref, watch } from 'vue';
import QRCode from 'qrcode';
import { Head, router } from '@inertiajs/vue3';

import AppLayout from '@/layouts/AppLayout.vue';
import TableToolbar from '@/components/TableToolbar.vue';
import Pagination from '@/components/Pagination.vue';

import { Button } from '@/components/ui/button';
import { Dialog, DialogContent, DialogHeader, DialogTitle, DialogDescription } from '@/components/ui/dialog';
import { Badge } from '@/components/ui/badge';
import { Separator } from '@/components/ui/separator';
import { ScrollArea } from '@/components/ui/scroll-area';
import { Card, CardHeader, CardTitle, CardContent } from '@/components/ui/card';
import { Table, TableBody, TableCell, TableCaption, TableHead, TableHeader, TableRow } from '@/components/ui/table';

import { QrCode, Trash2, Eye, Copy } from 'lucide-vue-next';
import { useConfirmDelete } from '@/composables/useConfirmDelete';
import type { BreadcrumbItem } from '@/types';

const props = defineProps<{ devices: any }>();

const { confirmDelete } = useConfirmDelete();

const breadcrumbs: BreadcrumbItem[] = [
  { title: 'Devices', href: route('devices.index') },
];

function onFilterChange(filters: any) {
  const cleanFilters = Object.fromEntries(
    Object.entries(filters).filter(([_, v]) => v !== '' && v !== null)
  ) as any;

  router.get(route('devices.index'), cleanFilters, {
    preserveState: true,
    replace: true,
    preserveScroll: true,
  });
}

function deleteDevice(id: string) {
  confirmDelete('devices.destroy', id);
}

const showQr = ref(false);
const selectedToken = ref('');
const qrImage = ref('');
const qrImgRef = ref<HTMLImageElement | null>(null);

watch(selectedToken, async (token) => {
  if (token) {
    qrImage.value = await QRCode.toDataURL(token);
  }
});

function generateQr(device: any) {
  selectedToken.value = device.auth_token;
  showQr.value = true;
}

function printQr() {
  if (!qrImgRef.value) return;
  const win = window.open('', '_blank');
  if (win) {
    const html = `
      <html>
        <head><title>Print QR Code</title></head>
        <body style="text-align:center; padding:30px">
          <img src="${qrImgRef.value.src}" style="max-width:300px" />
          <script>
            window.onload = function() {
              window.print();
              window.onafterprint = function() { window.close(); }
            }
          <\/script>
        </body>
      </html>
    `;
    win.document.write(html);
    win.document.close();
  }
}

function downloadQr() {
  if (!qrImgRef.value) return;
  const link = document.createElement('a');
  link.href = qrImgRef.value.src;
  link.download = 'device-qr-code.png';
  link.click();
}

function maskToken(token: string): string {
  if (!token) return '';
  const visible = token.slice(-4);
  const masked = '*'.repeat(token.length - 4);
  return masked + visible;
}

const showDeviceModal = ref(false);
const loadingDevice = ref(false);
const deviceDetails = ref<any>(null);
const deviceWidgets = ref<any[]>([]);
const tokenRevealed = ref(false);

const showDeviceDetails = async (device: any) => {
  try {
    loadingDevice.value = true;
    showDeviceModal.value = true;
    tokenRevealed.value = false;

    const id = device.id || device._id;
    const res = await fetch(route('devices.show', id));
    if (!res.ok) throw new Error('Failed to load device details');
    const json = await res.json();

    deviceDetails.value = json.device;
    deviceWidgets.value = json.widgets || [];
  } catch (e) {
    console.error(e);
  } finally {
    loadingDevice.value = false;
  }
};

function copyToken() {
  if (!deviceDetails.value?.auth_token) return;
  navigator.clipboard.writeText(deviceDetails.value.auth_token);
}

function maskedToken(t: string) {
  if (!t) return '';
  const visible = t.slice(-4);
  return '•'.repeat(Math.max(0, t.length - 4)) + visible;
}
</script>

<template>
  <Head title="Devices" />
  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="p-4">
      <TableToolbar :create-route="route('devices.create')" :on-filter-change="onFilterChange" :showUsedFilter="false" />
      <div v-if="devices.data.length" class="mt-4">
        <Table>
          <TableHeader>
            <TableRow>
              <TableHead>Factory Name</TableHead>
              <TableHead>Type</TableHead>
              <TableHead>Auth Token</TableHead>
              <TableHead>Actions</TableHead>
            </TableRow>
          </TableHeader>
          <TableBody>
            <TableRow v-for="device in devices.data" :key="device.id || device._id">
              <TableCell>{{ device.factory_name }}</TableCell>
              <TableCell>{{ device.type?.name || '—' }}</TableCell>
              <TableCell>{{ maskToken(device.auth_token) }}</TableCell>
              <TableCell class="flex flex-col sm:flex-row gap-2">
                <Button variant="outline" size="sm" @click="generateQr(device)">
                  <QrCode class="w-4 h-4 mr-1" /> Generate QR
                </Button>
                <Button variant="outline" size="sm" @click="showDeviceDetails(device)">
                  <Eye class="w-4 h-4 mr-1" /> Info
                </Button>
                <Button variant="ghost" size="sm" @click="deleteDevice(device.id || device._id)">
                  <Trash2 class="w-4 h-4 mr-1" />
                </Button>
              </TableCell>
            </TableRow>
          </TableBody>
          <TableCaption>
            <div v-if="devices.total > devices.per_page" class="row align-items-center mt-4">
              <div class="ml-auto">
                <Pagination :pagination="devices.links" />
              </div>
            </div>
          </TableCaption>
        </Table>
      </div>
    </div>

    <!-- QR Modal -->
    <Dialog v-model:open="showQr">
      <DialogContent>
        <DialogHeader>
          <DialogTitle>Device QR Code</DialogTitle>
        </DialogHeader>
        <div class="flex justify-center items-center py-4 flex-col gap-4">
          <img v-if="qrImage" :src="qrImage" alt="QR Code" ref="qrImgRef" class="max-w-[200px]" />
          <div class="flex gap-2">
            <Button variant="outline" size="sm" @click="printQr">🖨️ Print QR</Button>
            <Button variant="outline" size="sm" @click="downloadQr">💾 Download QR</Button>
          </div>
        </div>
      </DialogContent>
    </Dialog>

    <!-- Device Info Modal -->
    <Dialog v-model:open="showDeviceModal">
      <DialogContent class="max-w-2xl">
        <DialogHeader>
          <DialogTitle>Device Details</DialogTitle>
          <DialogDescription v-if="deviceDetails">
            {{ deviceDetails.factory_name }} — {{ deviceDetails.type || '—' }}
          </DialogDescription>
        </DialogHeader>

        <div v-if="loadingDevice" class="py-6 text-center">Loading...</div>
        <div v-else-if="deviceDetails" class="space-y-4">
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
            <div>
              <div class="text-xs text-muted-foreground">Factory Name</div>
              <div class="font-medium">{{ deviceDetails.factory_name || '—' }}</div>
            </div>
            <div>
              <div class="text-xs text-muted-foreground">Production Name</div>
              <div class="font-medium">{{ deviceDetails.production_name || '—' }}</div>
            </div>
            <div>
              <div class="text-xs text-muted-foreground">Type</div>
              <div class="font-medium">{{ deviceDetails.type || '—' }}</div>
            </div>
            <div>
              <div class="text-xs text-muted-foreground">Location</div>
              <div class="font-medium">
                {{ deviceDetails.site || '—' }}<span v-if="deviceDetails.area"> / {{ deviceDetails.area }}</span>
              </div>
            </div>
            <div class="sm:col-span-2">
              <div class="flex items-center justify-between">
                <div>
                  <div class="text-xs text-muted-foreground">Auth Token</div>
                  <div class="font-mono break-all">
                    {{ tokenRevealed ? deviceDetails.auth_token : maskedToken(deviceDetails.auth_token) }}
                  </div>
                </div>
                <div class="flex gap-2">
                  <Button variant="outline" size="sm" @click="tokenRevealed = !tokenRevealed">
                    {{ tokenRevealed ? 'Hide' : 'Show' }}
                  </Button>
                  <Button variant="outline" size="sm" @click="copyToken">
                    <Copy class="w-4 h-4 mr-1" /> Copy
                  </Button>
                </div>
              </div>
            </div>
            <div>
              <div class="text-xs text-muted-foreground">Status</div>
              <Badge :variant="deviceDetails.connection_status ? 'default' : 'secondary'">
                {{ deviceDetails.connection_status ? 'Online' : 'Offline' }}
              </Badge>
            </div>
            <div>
              <div class="text-xs text-muted-foreground">Activated At</div>
              <div class="font-medium">{{ deviceDetails.activated_at || '—' }}</div>
            </div>
          </div>

          <Separator />

          <div>
            <div class="mb-2 font-semibold">Widgets ({{ deviceWidgets.length }})</div>
            <ScrollArea class="h-[280px] pr-3">
              <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <Card v-for="w in deviceWidgets" :key="w.id" class="shadow-sm">
                  <CardHeader class="pb-2">
                    <CardTitle class="text-base">{{ w.name || 'Widget' }}</CardTitle>
                  </CardHeader>
                  <CardContent class="space-y-2 text-sm">
                   <div class="text-xs text-muted-foreground">
                      ID: {{ w.id }}
                    </div>
                    <div class="flex items-center gap-2">
                      <Badge :variant="w.visible ? 'default' : 'secondary'">
                        {{ w.visible ? 'Visible' : 'Hidden' }}
                      </Badge>
                    </div>
                    <div v-if="w.production_name" class="text-muted-foreground">
                      Prod: {{ w.production_name }}
                    </div>
                    <div v-if="w.factory_name" class="text-muted-foreground">
                      Factory: {{ w.factory_name }}
                    </div>
                    <div v-if="w.attributes && Object.keys(w.attributes).length" class="mt-2">
                      <div class="text-xs text-muted-foreground mb-1">Attributes</div>
                      <ul class="list-disc list-inside space-y-0.5">
                        <li v-for="(val, key) in w.attributes" :key="key">
                          <span class="font-medium">{{ key }}:</span> {{ String(val) }}
                        </li>
                      </ul>
                    </div>
                  </CardContent>
                </Card>
              </div>
            </ScrollArea>
          </div>
        </div>
      </DialogContent>
    </Dialog>
  </AppLayout>
</template>
