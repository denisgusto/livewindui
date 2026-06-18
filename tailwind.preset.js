/*
| LiveWindUI — Tailwind preset
|
| Add this to your tailwind.config.js so the accent tokens and dark-mode
| strategy used by the components are available in your build:
|
|   import liveWindUi from './vendor/denisgusto/livewindui/tailwind.preset.js';
|
|   export default {
|       presets: [liveWindUi],
|       content: [
|           './resources/**\/*.blade.php',
|           './vendor/denisgusto/livewindui/resources/views/**\/*.blade.php',
|       ],
|   };
|
| The preset enables `darkMode: 'class'` and exposes the `accent`,
| `accent-content` and `accent-foreground` colors backed by CSS variables
| (see resources/css/livewindui.css), so utilities like `bg-accent`,
| `text-accent-foreground` and `hover:bg-accent-content` work and adapt to
| light/dark automatically.
*/

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class',
    theme: {
        extend: {
            colors: {
                accent: 'rgb(var(--lw-accent) / <alpha-value>)',
                'accent-content': 'rgb(var(--lw-accent-content) / <alpha-value>)',
                'accent-foreground': 'rgb(var(--lw-accent-foreground) / <alpha-value>)',
            },
        },
    },
};
