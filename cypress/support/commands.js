// ***********************************************
// This example commands.js shows you how to
// create various custom commands and overwrite
// existing commands.
//
// For more comprehensive examples of custom
// commands please read more here:
// https://on.cypress.io/custom-commands
// ***********************************************
//
//
// -- This is a parent command --
// Cypress.Commands.add('login', (email, password) => { ... })
//
//
// -- This is a child command --
// Cypress.Commands.add('drag', { prevSubject: 'element'}, (subject, options) => { ... })
//
//
// -- This is a dual command --
// Cypress.Commands.add('dismiss', { prevSubject: 'optional'}, (subject, options) => { ... })
//
//
// -- This will overwrite an existing command --
// Cypress.Commands.overwrite('visit', (originalFn, url, options) => { ... })
Cypress.Commands.add("getRequest", (queryParam, counterValue) => {
  cy.request({
    method: "GET",
    url: "/count",
    qs: queryParam,
  }).then((response) => {
    // Check the response code
    expect(response.status).to.eq(200);
    expect(response.body).to.have.property("counter");
    expect(response.body.counter).to.be.a("number");
    expect(response.body.counter).to.equal(counterValue);
  });
});

Cypress.Commands.add("deleteRequest", () => {
  cy.request({
    method: "DELETE",
    url: "/truncate",
  }).then((response) => {
    expect(response.status).to.eq(204);
  });
});

Cypress.Commands.add("badRequest", (queryParam) => {
  cy.request({
    method: "GET",
    url: "/count",
    qs: queryParam,
    failOnStatusCode: false
  }).then((response) => {
    // Check the response code
    expect(response.status).to.eq(400);
    expect(response.body).to.have.property(
      "type",
      "/log-analytics-service/errors/retrieve_aggregated_log/bad-request"
    );
    expect(response.body).to.have.property("title", "Validation failed.");
    expect(response.body)
      .to.have.property("detail")
      .and.to.be.an("array")
      .that.includes("Provided Service Name Cannot be empty");
  });
});
