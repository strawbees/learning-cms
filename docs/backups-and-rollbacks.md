# Backups and rollbacks
The ideal way to handle a full backup and rollback of the application is by
using the "Snapshot" feature of Lightsail. By simply spinning a new instance
from a snapshot and then attaching the static IP to this instance, the
application will be instantly replaced.

## Creating snapshots
It is possible enable "Automatic Snapshots" either during the creation of the
instance, or later, on the instance's "Snapshots" section in the dashboard.
Automatic snapshots are saved daily and kept for a week.

It is also possible to take manual snapshots (for example after an important
content update) by going to the instance's snapshot dashboard and clicking on
"Manual snapshots > Create snapshot". Make sure you name the manual snapshot
appropriately, so it can be easier to understand what it refers too later.

**Each snapshot costs money. Make sure to manage them well and delete old and
unnecessary copies.**

## Spinning a new instance from a snapshot
Once you have the snapshot, click on it's menu (3 vertical dots) > "Create new
instance". Make sure to name the instance properly, eg.:
`application-instance-production-2019-11-30`.

Wait 2 or 3 minutes so the instance is completely booted.

## Attaching a new instance to the existing static IP
With the instance ready, you will need to attaching it to the existing static IP
address. You can do this directly from "Networking" section in the Lightsail
dashboard. Simply click on the IP you want to use, then detach it, then attach
it to the new instance.

**Each instance costs money. If you are satisfied with the rollback, delete the
replaced instance.**
