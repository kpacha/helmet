Feature: Evaluate responses to various HTTP methods.

Background:
  Given "curl" is installed
  And the following profile:
    | name     | value      |
    | hostname | google.com |

Scenario Outline: Verify server responds correctly to various HTTP methods
  When I launch a "curl" attack with:
    """
    -i -X <method> <hostname>
    """
  Then the output should contain "<response>"
  Examples:
    | method | response                       |
    | delete | Error 405 (Method Not Allowed) |
    | patch  | Error 405 (Method Not Allowed) |
    | trace  | Error 405 (Method Not Allowed) |
    | track  | Error 405 (Method Not Allowed) |
    | bogus  | Error 405 (Method Not Allowed) |