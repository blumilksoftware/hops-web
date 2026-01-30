Feature: User can see welcome page

  Scenario: Welcome page is accessible
    Given a user is requesting "/" using GET method
    When a request is sent
    Then the response should have status 200
