const { test, expect } = require('@playwright/test');

test('serves the Currencing conversion boundary in a real browser', async ({ page }) => {
  const response = await page.goto('/currencing/conversion-boundary');

  expect(response).not.toBeNull();
  expect(response.ok()).toBeTruthy();

  const payload = await response.json();

  expect(payload.dependencyDirection).toBe(
    'Exchanging may depend on Currencing; Currencing must not depend on Exchanging.',
  );
});
