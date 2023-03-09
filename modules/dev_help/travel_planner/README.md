## Travel Planner

The concept is that a client has requested that a Drupal site be created that helps their executive staff coordinate travel plans and calendar events. As a first step in creating this site create a custom Drupal module that exposes a service that accepts an email as input and a plugin to that service that parses the below email for airline reservations info. In future stories, we will create the plumbing for receiving the emails, and plugins to handle other types of emails, but for this task the service and the reservations plugin are the only things that are required.


 ### Reservation Content Type

| Field Name | Machine Name | Description     |
| :---------|:--------------|:-------------------------------------|
| airline    | airline      | The airline the reservation is with. |

### Disclaimer
The code in this repository is based on tested, working code. I am currently modifying and updating it so it be broken in the process.
