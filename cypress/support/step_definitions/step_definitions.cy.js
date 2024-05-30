/// <reference types="cypress"/>

import { Given, When, And, Then } from "cypress-cucumber-preprocessor/steps";

Given("To start importing logs", () => {
  cy.deleteRequest();
  cy.task("importLogs").then((result) => {
    expect(result).to.include("Storing service logs to DB started");
    expect(result).to.include("Storing service logs to DB Succeeded");
  });
});

Given("the following query parameters are set:", (dataTable) => {
  const queryParams = {};
  dataTable.hashes().forEach((row) => {
    queryParams[row.name] = row.value;
  });
  cy.wrap(queryParams).as("queryParams");
});

When("I request {string} using HTTP GET", (endpoint) => {
  cy.get("@queryParams").then((queryParams) => {
    cy.request({
      method: "GET",
      url: "/count",
      qs: queryParams,
    }).as("response");
  });
});

Then("the response code is 200", () => {
  cy.get("@response").its("status").should("eq", 200);
});

And("the response contains {int} as the counter value", (expectedCount) => {
  cy.get("@response").then((response) => {
    expect(response.body.counter).to.equal(expectedCount);
  });
});
