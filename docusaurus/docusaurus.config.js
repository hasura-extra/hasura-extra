// @ts-check
// Note: type annotations allow type checking and IDEs autocompletion

const lightCodeTheme = require('prism-react-renderer/themes/github');
const darkCodeTheme = require('prism-react-renderer/themes/dracula');

/** @type {import('@docusaurus/types').Config} */
const config = {
  title: 'Hasura Extra',
  tagline: 'Unofficial PHP libraries for Hasura graphql engine.',
  url: 'https://hasura-extra.github.io',
  baseUrl: '/',
  onBrokenLinks: 'throw',
  onBrokenMarkdownLinks: 'warn',
  favicon: 'img/favicon.ico',
  organizationName: 'hasura-extra',
  projectName: 'hasura-extra.github.io',
  deploymentBranch: 'main',
  trailingSlash: false,
  presets: [
    [
      '@docusaurus/preset-classic',
      /** @type {import('@docusaurus/preset-classic').Options} */
      ({
        docs: {
          editCurrentVersion: true,
          sidebarPath: require.resolve('./sidebars.js'),
          editUrl: ({locale, versionDocsDirPath, docPath, version}) => {
            if (locale === 'en') {
              return `https://github.com/hasura-extra/hasura-extra/edit/main/docusaurus/${versionDocsDirPath}/${docPath}`;
            }

            return `https://github.com/hasura-extra/hasura-extra/edit/main/docusaurus/i18n/${locale}/docusaurus-plugin-content-docs/${version}/${docPath}`;
          },
          routeBasePath: '/',
          showLastUpdateAuthor: true,
          showLastUpdateTime: true,
        },
        theme: {
          customCss: require.resolve('./src/css/custom.css'),
        },
      }),
    ],
  ],
  themeConfig:
    /** @type {import('@docusaurus/preset-classic').ThemeConfig} */
    ({
      image: 'img/logo-text.png',
      navbar: {
        title: 'Hasura Extra',
        logo: {
          alt: 'Logo',
          src: 'img/logo.png',
        },
        items: [
          {
            type: 'localeDropdown',
            position: 'right',
          },
          {
            href: 'https://github.com/hasura-extra',
            className: 'header-github-link',
            'aria-label': 'GitHub repository',
            position: 'right',
          },
        ],
      },
      footer: {
        style: 'light',
        "logo": {
          "src": "img/logo-text.png",
          "href": "/",
        },
        copyright: `Copyright © ${new Date().getFullYear()} <a href='https://github.com/vuongxuongminh/'>Minh Vuong.</a>`,
      },
      prism: {
        theme: lightCodeTheme,
        darkTheme: darkCodeTheme,
        additionalLanguages: ['php'],
      },
    }),

  i18n: {
    defaultLocale: 'en',
    locales: ['en', 'vi'],
  },
};

module.exports = config;
