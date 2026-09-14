const { defineConfig } = require('@playwright/test');

const port = process.env.CURRENCING_E2E_PORT || '8127';
const baseURL = process.env.CURRENCING_BASE_URL || `http://127.0.0.1:${port}`;

module.exports = defineConfig({
  testDir: './tests/Ui',
  fullyParallel: true,
  forbidOnly: Boolean(process.env.CI),
  retries: process.env.CI ? 2 : 0,
  reporter: 'list',
  webServer: {
    command: `php -S 127.0.0.1:${port} -t public public/index.php`,
    url: `${baseURL}/currencing/conversion-boundary`,
    reuseExistingServer: false,
    timeout: 120000,
  },
  use: {
    baseURL,
    trace: 'retain-on-failure',
  },
});
