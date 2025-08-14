## **Repositories Overview**

### **1. `infra-automation` (GitOps Pipeline)**

**Purpose:** Build and configure infrastructure while ensuring security compliance.

* **Infrastructure Provisioning:**

  * Use **Terraform** to build the infrastructure.
  * Run **`tfsec`** for infrastructure security scanning.
* **Configuration Management:**

  * Use **Ansible** to install dependencies and perform system configuration.

---

### **2. `CI/CD-Pipeline`**

**Purpose:** Automate testing, security scanning, and application deployment.

#### **CI Pipeline:**

1. Run **Unit Tests** (if available) to validate application logic.
2. Perform **Security Testing**:

   * **Static Application Security Testing (SAST)**
   * **Dynamic Application Security Testing (DAST)**
   * **Docker image vulnerability scanning**
   * **Dependency/library security checks**

#### **CD Pipeline:**

* **Docker Compose Deployments:**

  * Deploy application via **SSH** or **AWS SSM**.
* **Kubernetes Deployments:**

  * Use **GitOps** approach with **ArgoCD** to automatically deploy updates to infrastructure and applications.





<!-- Dockerfile -->

<!-- NPM -->
npm install
npm run build <!-- npm run dev -->

<!-- PHP -->
composer install # update
php artisan key:generate
php artisan ser

<!-- MongoDB env -->
MONGO_DB_CONNECTION=mongodb
MONGO_DB_HOST=3.64.88.86
MONGO_DB_PORT=27017
MONGO_DB_DATABASE=sync-n
MONGO_DB_USERNAME=syncnadmin
MONGO_DB_PASSWORD=Sync_Nr00t!$yncl0uds135792468

<!-- MYSQL env -->
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=  root
DB_PASSWORD=



<!-- At docker compose run command  in the app-service -->
php atrisan migrate
php atrisan seed


<!-- =============================== -->
<!-- MYSQL service inside docker compose-->
image>> mysql:9.4.0
expose 3306
expose 27017


<!-- in the docker compose file -->
  # # MongoDB connection configuration
  # MONGO_DB_CONNECTION: mongodb
  # MONGO_DB_HOST: 3.64.88.86
  # MONGO_DB_PORT: 27017
  # MONGO_DB_DATABASE: sync-n
  # MONGO_DB_USERNAME: syncnadmin
  # MONGO_DB_PASSWORD: Sync_Nr00t!$yncl0uds135792468



  <!-- amirbasiony66@nNwBzzEHeyUTtFtw -->

  <!-- mongodb+srv://amirbasiony66:nNwBzzEHeyUTtFtw@sync-n.vrwjyej.mongodb.net/?retryWrites=true&w=majority&appName=sync-n -->