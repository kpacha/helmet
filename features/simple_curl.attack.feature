Feature: Launch curl attack

Background:
  Given "curl" is installed
  And the following profile:
    | name     | value      |
    | hostname | google.com |

Scenario: Verify a 301 is received from a curl
  When I launch a "curl" attack with:
    """
    --silent --output /dev/null --write-out "%{http_code}" <hostname>
    """
  Then it should pass with exactly:
    """
    301
    """