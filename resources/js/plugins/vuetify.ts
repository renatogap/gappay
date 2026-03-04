/**
 * plugins/vuetify.ts
 *
 * Framework documentation: https://vuetifyjs.com`
 */

// Styles
import '@mdi/font/css/materialdesignicons.css'
import 'vuetify/styles'

// Composables
import { createVuetify } from 'vuetify'
import { pt } from 'vuetify/locale'
import { skeletonLightTheme } from '@/themes/SkeletonLightTheme'
// import { rosaLightTheme } from '@/themes/RosaTheme'

// https://vuetifyjs.com/en/introduction/why-vuetify/#feature-guides
export default createVuetify({
    defaults: {
        VAutocomplete: {
            density: 'comfortable',
            variant: 'outlined',
            color: 'primary',
        },
        VCombobox: {
            density: 'comfortable',
            variant: 'outlined',
            color: 'primary',
        },
        VDataTable: {
            density: 'comfortable',
        },
        VSelect: {
            density: 'comfortable',
            variant: 'outlined',
            color: 'primary',
        },
        VSwitch: {
            density: 'comfortable',
            variant: 'outlined',
            color: 'primary',
        },
        VTextField: {
            density: 'comfortable',
            variant: 'outlined',
            color: 'primary',
        },
    },
    locale: {
        locale: 'pt',
        messages: { pt },
    },
    theme: {
        defaultTheme: 'skeletonLightTheme',
        themes: { skeletonLightTheme },
    },
})
