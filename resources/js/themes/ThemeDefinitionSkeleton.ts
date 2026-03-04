import {ThemeDefinition} from "vuetify";

interface ThemeDefinitionSkeleton extends ThemeDefinition {
  dark: boolean;
  colors?: {
    background?: string;
    surface?: string;
    primary?: string;
    secondary?: string;
    menu?: string;
    avatar?: string;
    'secondary-darken-1'?: string;
    error?: string;
    info?: string;
    success?: string;
    warning?: string;
  };

}

export {ThemeDefinitionSkeleton}
