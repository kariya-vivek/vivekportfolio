import os
import re

user_dir = r"C:\xampp\htdocs\craftzon\user"

# Modify crafthome.php
crafthome_path = os.path.join(user_dir, 'crafthome.php')
with open(crafthome_path, 'r', encoding='utf-8') as f:
    content = f.read()

# Remove login redirect
content = re.sub(
    r'\$us_profile = \$_SESSION\["users_id"\];\s*\n\s*\$usid = \$us_profile;\s*\n\s*if \(\$us_profile == false\) \{\s*\n\s*header\(\'location:logincraft.php\'\);\s*\n\s*exit\(\);\s*\n\s*\}',
    '$usid = isset($_SESSION["users_id"]) ? $_SESSION["users_id"] : 0;',
    content
)

# Update the top bar to show Login/Register if guest
login_html = """
    <?php if ($usid == 0): ?>
        <li style="margin-left:auto; list-style:none;">
            <a href="logincraft.php" style="color:white; font-weight:bold; text-decoration:none;">Login / Register</a>
        </li>
    <?php else: ?>
        <!-- ORIGINAL PROFILE DROPDOWN -->
"""

content = content.replace('<li class="nav-item dropdown ms-auto d-flex align-items-center">', 
                          '<?php if ($usid == 0): ?>\n<li class="nav-item ms-auto d-flex align-items-center"><a href="logincraft.php" style="color:white; font-weight:bold; text-decoration:none; margin-right:15px;"><i class="fa fa-user"></i> Login / Register</a></li>\n<?php else: ?>\n<li class="nav-item dropdown ms-auto d-flex align-items-center">')

# We need to close the else block after the dropdown.
# The dropdown ends at </ul> </li>.
# It's tricky to regex this. We can just inject the if condition carefully.
