/// <reference types='Cypress'/>

let data;

describe("API Tests", () => {
  beforeEach(() => {
    cy.clearCookies();
    cy.fixture("data").then((sel) => {
      data = sel;
    });
  });

  it("GET Request API Test", () => {
    cy.deleteRequest();
    cy.task("importLogs").then((result) => {
      expect(result).to.include("Storing service logs to DB started");
      expect(result).to.include("Storing service logs to DB Succeeded");

      // user service tests
      cy.getRequest(data.user_service_count, data.user_service_count_value);

      cy.getRequest(
        data.user_service_count_201,
        data.user_service_count_201_value
      );
      cy.getRequest(
        data.user_service_count_400,
        data.user_service_count_400_value
      );

      // invoice service tests
      cy.getRequest(
        data.invoice_service_count,
        data.invoice_service_count_value
      );
      cy.getRequest(
        data.invoice_service_count_201,
        data.invoice_service_count_201_value
      );
      cy.getRequest(
        data.invoice_service_count_400,
        data.invoice_service_count_400_value
      );
      cy.getRequest(
        data.user_service_count_date,
        data.user_service_count_date_value
      );
      cy.getRequest(
        data.invoice_service_count_date,
        data.invoice_service_count_date_value
      );
      cy.badRequest(data.no_user_service_name);
    });
      cy.badRequest(data.no_invoice_service_name);
  });
  
  it("DELETE Request API Test", () => {
    cy.deleteRequest();
  });
});
