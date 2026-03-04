// import {type ThemeDefinition} from "vuetify";
import { type ThemeDefinitionSkeleton } from './ThemeDefinitionSkeleton'

const skeletonLightTheme: ThemeDefinitionSkeleton = {
  dark: false,
  colors: {
    background: '#EEEEEE',
    // surface: '#FFFFFF',
    // primary: '#6200EE',
    // secondary: '#03DAC6',
    menu: '#424242',
    avatar: '#FBC02D',
    // 'secondary-darken-1': '#018786',
    // erroValidacao: 'black',
    // info: '#2196F3',
    // success: '#4CAF50',
    // warning: '#FB8C00',
  },
}
const skeletonDarkTheme: ThemeDefinitionSkeleton = {
  dark: false,
  colors: {
  //   background: '#FFFFFF',
  //   surface: '#FFFFFF',
  //   primary: '#6200EE',
  //   'primary-darken-1': '#3700B3',
  //   secondary: '#03DAC6',
  //   'secondary-darken-1': '#018786',
  //   error: 'black',
  //   info: '#2196F3',
  //   success: '#4CAF50',
  //   warning: '#FB8C00',
  },
}

export { skeletonLightTheme, skeletonDarkTheme }
