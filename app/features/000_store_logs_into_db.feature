@store_logs_into_db
Feature: As an Analyser of the different services logs
  I want to store different services' logs into the db
  So that I can query them later based on aggregated count

  Scenario: Store the provided logs into the db
    Given I want to store logs with the following data:
      | service_name    | date_time                  | http_method | route     | protocol | http_code |
      | USER-SERVICE    | 17/Aug/2018:09:21:53 +0000 | POST        | /users    | HTTP/1.1 | 201       |
      | USER-SERVICE    | 17/Aug/2018:09:21:54 +0000 | POST        | /users    | HTTP/1.1 | 400       |
      | INVOICE-SERVICE | 17/Aug/2018:09:21:55 +0000 | POST        | /invoices | HTTP/1.1 | 201       |
      | USER-SERVICE    | 17/Aug/2018:09:21:56 +0000 | POST        | /users    | HTTP/1.1 | 201       |

