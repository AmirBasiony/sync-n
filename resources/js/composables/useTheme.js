import {useColorMode} from '@vueuse/core';

export default function useTheme() {
    const theme = useColorMode({
        attribute: 'theme',
        modes: {
            light: 'light',
            dark: 'dark'
        }
    });
    theme.system.value = 'light';

    const setTheme = (newTheme) => {
        localStorage.setItem('theme', newTheme);
        theme.value = newTheme;
    };

    return { theme, setTheme };
};
