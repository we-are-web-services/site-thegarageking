# Launch checklist

Follow the hub's ground rules: say exactly what will change, back up first, wait for David's go-ahead, check afterward, record it.

1. Replace placeholder content with the real content (kept out of this repo).
2. Put the finished theme and plugins on the server. **Deploy step not designed yet** (hub to-do 21); until then it is done by hand with David.
3. Install the standard plugins (`waws-hub/docs/standard-plugins.md`). Delete Hello Dolly and Akismet if the install includes them.
4. Switch Under Construction off at launch (assumed; confirm in `standard-plugins.md`).
5. Run the server's new-site checklist: `impossible-socks-server/docs/new-site-checklist.md` (it includes the XML-RPC rule).
6. Join the standard access: `claude-waws` user and Network secret, Simple History, David's `cdt24` admin
   (see `waws-hub/docs/cloud-access-plan.md` and `impossible-socks-server/docs/admin-accounts.md`).
7. Add the site to In The Loop, Matomo and WHMCS where they apply.
8. Check the live site, record the launch date here, then turn this repo into an archive.
