@retrieve_aggregated_count
Feature: As an Analyser of the different services logs
  I want to retrieve the aggregated count of the logs with different criteria

  Scenario: Retrieve all aggregated count with all Criteria provided
    Given the following query parameters are set:
      | name           | value                |
      | serviceNames[] | USER-SERVICE         |
      | startDate      | 2017-04-01T00:00:00Z |
      | endDate        | 2024-04-30T23:59:59Z |
      | statusCode     | 201                  |
    When I request "/count" using HTTP GET
    Then the response code is 200
    And the response body matches:
        """
        {"counter":2}
        """

    Scenario: Retrieve only the aggregated logs belong to INVOICE-SERVICE service
      Given the following query parameters are set:
        | name           | value           |
        | serviceNames[] | INVOICE-SERVICE |
      When I request "/count" using HTTP GET
      Then the response code is 200
      And the response body matches:
            """
            {"counter":1}
            """

  Scenario: Retrieve only the aggregated logs belong to USER-SERVICE service
    Given the following query parameters are set:
      | name           | value           |
      | serviceNames[] | USER-SERVICE |
    When I request "/count" using HTTP GET
    Then the response code is 200
    And the response body matches:
            """
            {"counter":3}
            """

  Scenario: Retrieve the aggregated between specific dates
    Given the following query parameters are set:
      | name           | value           |
      | startDate      | 2018-08-17 09:21:54 |
      | endDate        | 2018-08-17 09:21:55 |
    When I request "/count" using HTTP GET
    Then the response code is 200
    And the response body matches:
            """
            {"counter":2}
            """

  Scenario: Retrieve the aggregated counts with specific http code
    Given the following query parameters are set:
      | name           | value           |
      | statusCode     | 400             |
    When I request "/count" using HTTP GET
    Then the response code is 200
    And the response body matches:
            """
            {"counter":1}
            """
