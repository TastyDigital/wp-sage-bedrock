/**
 * Compiler configuration
 *
 * @see {@link https://roots.io/sage/docs sage documentation}
 * @see {@link https://bud.js.org/learn/config bud.js configuration guide}
 *
 * @type {import('@roots/bud').Config}
 */
export default async (app) => {
  /**
   * Application assets & entrypoints
   *
   * @see {@link https://bud.js.org/reference/bud.entry}
   * @see {@link https://bud.js.org/reference/bud.assets}
   */
  app
    .provide({ jquery: ["jQuery", "$"]})
    .entry('app', ['@scripts/app', '@styles/app'])
    .entry('editor', ['@scripts/editor', '@styles/editor'])
    .assets(['images']);

  /**
   * Set public path
   *
   * @see {@link https://bud.js.org/reference/bud.setPublicPath}
   */
  app.setPublicPath('/app/themes/tasty-starter/public/');

  /**
   * Development server settings
   *
   * @see {@link https://bud.js.org/reference/bud.setUrl}
   * @see {@link https://bud.js.org/reference/bud.setProxyUrl}
   * @see {@link https://bud.js.org/reference/bud.watch}
   */
  app
    .setUrl('http://localhost:3000')
    .setProxyUrl('http://tasty-starter.local')
    .watch(['resources/views', 'app']);

  /**
   * Generate WordPress `theme.json`
   *
   * @note This overwrites `theme.json` on every build.
   *
   * @see {@link https://bud.js.org/extensions/sage/theme.json}
   * @see {@link https://developer.wordpress.org/block-editor/how-to-guides/themes/theme-json}
   */
  app.wpjson
      .setOption('styles', {
          typography: {
              fontFamily: 'var(--wp--preset--font-family--sans)',
              lineHeight: "1.4",
          },
          elements: {
              button: {
                  "color": {
                      "background": "var(--wp--preset--color--blue-500)",
                      "text": "var(--wp--preset--color--white)"
                  },
                  "typography": {
                      "textDecoration": "none",
                      "textDecorationLine":"none",
                      "fontWeight": "bold"
                  },
                  "border": {
                      "radius": "0"
                  },
                  ":hover": {
                      "color": {
                          "background": "var(--wp--preset--color--blue-700)"
                      },
                      "typography": {
                          "textDecoration": "none"
                      }
                  },
                  ":focus": {
                      "color": {
                          "background": "var(--wp--preset--color--blue-700)"
                      }
                  },
                  ":active": {
                      "color": {
                          "background": "var(--wp--preset--color--blue-700)"
                      }
                  }
              },
          }
      })
    .setSettings({
      background: {
        backgroundImage: true,
      },
      color: {
        custom: false,
        customDuotone: false,
        customGradient: false,
        defaultDuotone: false,
        defaultGradients: false,
        defaultPalette: false,
        duotone: [],
      },
      custom: {
        spacing: {},
        typography: {
          'font-size': {},
          'line-height': {},
        },
      },
      spacing: {
        padding: true,
        units: ['px', '%', 'em', 'rem', 'vw', 'vh'],
      },
        typography: {
            customFontSize: false,
            lineHeight: false,
            fontWeight: false,
            fontStyle: false,
            textTransform: true
        },
        layout: {
            contentSize: "720px",
            wideSize: "1338px"
        },
    })
    .useTailwindColors()
    .useTailwindFontFamily()
    .useTailwindFontSize()
    .set('settings.blocks.core/button.border.radius', false)
    .set('settings.border.radius', false)
    .enable();
};
