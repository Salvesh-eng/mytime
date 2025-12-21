#!/usr/bin/env python3
import os

file_path = r'd:\Mytime\resources\views\admin\financial\income.blade.php'

with open(file_path, 'r', encoding='utf-8') as f:
    content = f.read()

# Fix the missing comma
content = content.replace(
    "addEventListener('DOMContentLoaded' function()",
    "addEventListener('DOMContentLoaded', function()"
)

with open(file_path, 'w', encoding='utf-8') as f:
    f.write(content)

print("File fixed successfully!")
