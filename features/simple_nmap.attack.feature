# nmap-simple.attack
Feature: simple nmap attack to check for open ports

  Background:
    Given "nmap" is installed
    And the following profile:
      | name     | value       |
      | hostname | example.com |

  Scenario: Check standard web ports
    When I launch a "nmap" attack with:
      """
      -F <hostname>
      """
    Then it should pass with regexp:
      """
      /80.tcp\s+open/
      """
    Then the output should not match:
      """
      25\/tcp\s+open
      """