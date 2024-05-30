@truncate_service_logs
Feature: As an Analyser of the different services logs
  I want to truncate the service_log table

  Scenario: Remove all the records in the service_log table
    When I request "/truncate" using HTTP DELETE
    Then the response code is 204
