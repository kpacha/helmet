Feature: Launch generic attack

This attack adapter allows for any command line binary to be executed and the output parsed. This can be used to run new attacks not yet supported by gauntlt. It can also be used to run custom scripts created by the user, which can allow for gauntlt attacks to be fully customized.

Background:
  Given the "ping" command line binary is installed
  And the following profile:
    | name     | value      |
    | hostname | google.com |

Scenario: Verify a 301 is received from a curl
  When I launch a "generic" attack with:
    """
    -c 1 <hostname>
    """
  Then it should pass with regexp:
    """
    1 packets transmitted, 1 (packets )?received, 0(\.0)?% packet loss
    """