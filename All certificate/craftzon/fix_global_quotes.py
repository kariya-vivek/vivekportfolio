import os

bad_script = """var btn = e.target.querySelector('button[type="submit"], input[type="submit"]');"""
good_script = """var btn = e.target.querySelector("button[type='submit'], input[type='submit']");"""

bad_script2 = """if (btn.tagName === 'BUTTON') {"""
good_script2 = """if (btn.tagName === "BUTTON") {"""

bad_script3 = """} else if (btn.tagName === 'INPUT') {"""
good_script3 = """} else if (btn.tagName === "INPUT") {"""

for directory in ['user', 'seller']:
    if not os.path.exists(directory):
        continue
    for file in os.listdir(directory):
        if file.endswith('.php'):
            filepath = os.path.join(directory, file)
            with open(filepath, 'r', encoding='utf-8', errors='ignore') as f:
                content = f.read()
            
            # Since some single quotes might break echo "...", actually I should just change ALL double quotes inside the script to single quotes, OR if it's inside `echo "..."` I should escape them.
            # Wait, `echo " ... ";` uses double quotes. So ANY double quote in the HTML breaks it!
            # The HTML attributes should use single quotes. So my script has double quotes `"` in `button[type="submit"]`. I should change them to single quotes!
            # Let's replace the whole script block!
            
            pass

