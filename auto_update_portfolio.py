import os
import re
import json

HTML_PATH = "index.html"
CERTS_DIR = os.path.join("assets", "certificates")
PROJECTS_DIR = os.path.join("assets", "projects")

def format_title(filename):
    name = os.path.splitext(filename)[0]
    name = name.replace('_certificate', '').replace('_certificates', '').replace('certificate', '')
    name = name.replace('-', ' ').replace('_', ' ').strip().title()
    return name

def main():
    if not os.path.exists(HTML_PATH):
        print(f"Error: {HTML_PATH} not found. Please run this script from the same folder as index.html.")
        return

    with open(HTML_PATH, 'r', encoding='utf-8') as f:
        html = f.read()

    changed = False

    # --- PROCESS CERTIFICATES ---
    print("Checking certificates...")
    cert_match = re.search(r'const certificateData = (\[.*?\]);', html, re.DOTALL)
    if not cert_match:
        print("Error: Could not find certificateData array in index.html.")
        return
    
    try:
        certs = json.loads(cert_match.group(1))
    except json.JSONDecodeError as e:
        print(f"Error: Could not parse certificateData. Please manually check index.html syntax. Error: {e}")
        input("Press Enter to exit...")
        return

    # Filter out deleted certificates
    valid_certs = []
    for c in certs:
        img_path = c.get('image', '')
        filename = os.path.basename(img_path)
        full_path = os.path.join(CERTS_DIR, filename)
        if os.path.exists(full_path):
            valid_certs.append(c)
        else:
            print(f"Removing deleted certificate: {filename}")
            changed = True

    existing_cert_files = set([os.path.basename(c.get('image', '')) for c in valid_certs])
    
    if os.path.exists(CERTS_DIR):
        for f in os.listdir(CERTS_DIR):
            if f.lower().endswith(('.jpg', '.png', '.jpeg', '.webp')) and f not in existing_cert_files:
                print(f"Adding new certificate: {f}")
                title = format_title(f)
                valid_certs.append({
                    "title": title,
                    "issuer": "Certification Authority",
                    "description": "Professional certification completed to advance technical and soft skills.",
                    "category": "Completed",
                    "image": f"./assets/certificates/{f}",
                    "link": "#"
                })
                changed = True
    
    if changed:
        new_cert_str = "const certificateData = " + json.dumps(valid_certs, indent=4) + ";"
        html = html[:cert_match.start()] + new_cert_str + html[cert_match.end():]

    # --- PROCESS PROJECTS ---
    print("Checking projects...")
    
    # Reload html matches in case the previous replacement shifted string indices
    proj_match = re.search(r'const projectData = (\[.*?\]);', html, re.DOTALL)
    if not proj_match:
        print("Error: Could not find projectData array in index.html.")
        return
    
    try:
        projects = json.loads(proj_match.group(1))
    except json.JSONDecodeError as e:
        print(f"Error: Could not parse projectData. Please manually check index.html syntax. Error: {e}")
        input("Press Enter to exit...")
        return
        
    valid_projects = []
    for p in projects:
        img_path = p.get('image', '')
        filename = os.path.basename(img_path)
        full_path = os.path.join(PROJECTS_DIR, filename)
        if os.path.exists(full_path):
            valid_projects.append(p)
        else:
            print(f"Removing deleted project: {filename}")
            changed = True

    existing_proj_files = set([os.path.basename(p.get('image', '')) for p in valid_projects])
    
    if os.path.exists(PROJECTS_DIR):
        for f in os.listdir(PROJECTS_DIR):
            if f.lower().endswith(('.jpg', '.png', '.jpeg', '.webp')) and f not in existing_proj_files:
                print(f"Adding new project: {f}")
                title = format_title(f)
                valid_projects.append({
                    "id": title.lower().replace(' ', '-'),
                    "category": "web",
                    "title": title,
                    "desc": "A new project automatically added to the portfolio.",
                    "image": f"./assets/projects/{f}",
                    "tech": ["HTML", "CSS", "JS"],
                    "liveUrl": None,
                    "githubUrl": None,
                    "reportUrl": None
                })
                changed = True
                
    if changed:
        new_proj_str = "const projectData = " + json.dumps(valid_projects, indent=4) + ";"
        html = html[:proj_match.start()] + new_proj_str + html[proj_match.end():]

    if changed:
        with open(HTML_PATH, 'w', encoding='utf-8') as f:
            f.write(html)
        print("Successfully updated index.html with changes!")
    else:
        print("No changes needed. Everything is already up to date!")

    input("Press Enter to exit...")

if __name__ == "__main__":
    main()
