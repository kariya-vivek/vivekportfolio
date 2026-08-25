import os
import re
import json

root_dir = r"C:\xampp\htdocs\craftzon"
result = {
    "files": [],
    "get_params": [],
    "sql_queries": [],
    "directories": []
}

for root, dirs, files in os.walk(root_dir):
    rel_root = os.path.relpath(root, root_dir)
    if rel_root != '.':
        result['directories'].append(rel_root)
        
    for f in files:
        if f.endswith('.php'):
            path = os.path.join(root, f)
            rel_path = os.path.relpath(path, root_dir)
            result['files'].append(rel_path)
            
            with open(path, 'r', encoding='utf-8', errors='ignore') as file:
                content = file.read()
            
            # Find GET/REQUEST params
            gets = re.findall(r'\$_(?:GET|REQUEST)\[[\'"]([^\'"]+)[\'"]\]', content)
            
            for g in set(gets):
                result['get_params'].append({'file': rel_path, 'param': g})
                
with open('audit_result.json', 'w') as f:
    json.dump(result, f, indent=4)
print("Audit script finished.")
