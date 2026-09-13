const { defineConfig } = require('@playwright/test');

module.exports = defineConfig({
  testDir: './tests/Ui',
  fullyParallel: true,
  forbidOnly: Boolean(process.env.CI),
  retries: process.env.CI ? 2 : 0,
  reporter: 'list',
  webServer: {
    command: 'php -S 127.0.0.1:8000 -t public public/index.php',
    url: 'http://127.0.0.1:8000/currencing/conversion-boundary',
    reuseExistingServer: !process.env.CI,
    timeout: 120000,
  },
  use: {
    baseURL: process.env.CURRENCING_BASE_URL || 'http://127.0.0.1:8000',
    trace: 'retain-on-failure',
  },
});
