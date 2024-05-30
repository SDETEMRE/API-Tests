Feature: Validate the functionality and expected responses for each API call

  Scenario Outline: Retrieve aggregated count with provided criteria
    Given To start importing logs

    Given the following query parameters are set:
      | name           | value     |
      | serviceNames[] | <service> |
      | startDate      | <start>   |
      | endDate        | <end>     |
      | statusCode     | <status>  |

    When I request "/count" using HTTP GET

    Then the response code is 200

    And the response contains <count> as the counter value

    Examples:
      | service         | start                | end                  | status | count |
      | USER-SERVICE    | 2017-04-01T00:00:00Z | 2024-05-30T00:00:00Z | 201    | 10    |
      | USER-SERVICE    | 2017-04-01T00:00:00Z | 2024-05-30T00:00:00Z | 400    | 3     |
      | INVOICE-SERVICE | 2017-04-01T00:00:00Z | 2024-05-30T00:00:00Z | 201    | 5     |
      | INVOICE-SERVICE | 2017-04-01T00:00:00Z | 2024-05-30T00:00:00Z | 400    | 1     |
      | USER-SERVICE    | 2018-08-17T00:00:00Z | 2018-08-18T00:00:00Z | 201    | 7     |





