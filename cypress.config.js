const { exec } = require("child_process");
const { defineConfig } = require("cypress");
const cucumber = require("cypress-cucumber-preprocessor").default;
module.exports = {
  e2e: {
    setupNodeEvents(on, config) {
      on("file:preprocessor", cucumber());

      on("task", {
        importLogs: () => {
          return new Promise((resolve, reject) => {
            exec("make import-logs", (error, stdout, stderr) => {
              if (error) {
                console.error(`exec error: ${error}`);
                return reject(error);
              }
              console.log(`stdout: ${stdout}`);
              console.error(`stderr: ${stderr}`);
              resolve(stdout);
            });
          });
        },
      });
    },
    baseUrl: "http://127.0.0.1:8090",
    watchForFileChanges: true,
    defaultCommandTimeout: 3000,
    requestTimeout: 30000,
    responseTimeout: 30000,
    pageLoadTimeout: 12000,
    viewportHeight: 1080,
    viewportWidth: 1900,
    execTimeout: 10000,
    testIsolation: false,
    retries: {
      runMode: 0,
      openMode: 0,
    },
    specPattern: [
      "cypress/e2e/**/*.feature",
      "cypress/e2e/**/*.cy.{js,jsx,ts,tsx}",
    ],
    screenshotOnRunFailure: true,
    video: false,
    trashAssetsBeforeRuns: true,
  },
};
