import re

with open('resources/views/admin/financial/index.blade.php', 'r', encoding='utf-8') as f:
    lines = f.readlines()

output = []
i = 0
while i < len(lines):
    line = lines[i]
    
    # Check if this is a button line with action-btn in income section (check for amount-income nearby)
    if 'class="action-btn" onclick="viewTransaction' in line and i > 0:
        # Check if we're in income section (look backwards for amount-income)
        is_income_section = False
        for j in range(max(0, i-5), i):
            if 'amount-income' in lines[j]:
                is_income_section = True
                break
        
        if is_income_section:
            # Replace with SVG icons
            indent = len(line) - len(line.lstrip())
            svg_view = '''                                    <button class="action-btn view-btn" onclick="viewTransaction({{ $transaction->id }})" title="View">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                            <circle cx="12" cy="12" r="3"></circle>
                                        </svg>
                                    </button>\n'''
            output.append(svg_view)
            
            # Skip the old view button line
            i += 1
            
            # Add delete button (next line should be delete button)
            if i < len(lines) and 'onclick="deleteTransaction' in lines[i]:
                svg_delete = '''                                    <button class="action-btn delete-btn" onclick="deleteTransaction({{ $transaction->id }})" title="Delete">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <polyline points="3 6 5 6 21 6"></polyline>
                                            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                            <line x1="10" y1="11" x2="10" y2="17"></line>
                                            <line x1="14" y1="11" x2="14" y2="17"></line>
                                        </svg>
                                    </button>\n'''
                output.append(svg_delete)
                i += 1
            else:
                output.append(lines[i])
                i += 1
            continue
    
    output.append(line)
    i += 1

with open('resources/views/admin/financial/index.blade.php', 'w', encoding='utf-8') as f:
    f.writelines(output)

print('File updated successfully')
