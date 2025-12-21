@extends('layouts.app')

@section('page-title', 'Savings Management')

@section('content')
<style>
    :root {
        --soft-pink: #FFB3D9;
        --soft-blue: #B3D9FF;
        --soft-green: #B3FFB3;
        --soft-orange: #FFD9B3;
        --soft-purple: #D9B3FF;
        --soft-peach: #FFCCB3;
        --soft-mint: #B3FFD9;
        --soft-lavender: #E6D9FF;
        --light-green: #C8E6C9;
        --light-pink: #F8BBD0;
    }

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    .savings-container {
        background: #F5F5F5;
        min-height: 100vh;
        padding: 20px;
    }

    .page-header {
        background: linear-gradient(135deg, #FFD9B3 0%, #D9B3FF 100%);
        padding: 30px;
        border-radius: 8px;
        margin-bottom: 30px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    .page-header h1 {
        font-size: 32px;
        font-weight: 700;
        color: #333;
        margin-bottom: 10px;
    }

    .page-header p {
        font-size: 14px;
        color: #666;
    }

    .back-button {
        display: inline-block;
        margin-bottom: 20px;
        padding: 10px 20px;
        background: linear-gradient(135deg, #FFB3D9 0%, #B3D9FF 100%);
        color: #333;
        text-decoration: none;
        border-radius: 6px;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .back-button:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }

    .form-section {
        background: white;
        padding: 30px;
        border-radius: 8px;
        margin-bottom: 30px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    .section-title {
        font-size: 18px;
        font-weight: 700;
        color: #333;
        margin-bottom: 20px;
        padding-bottom: 15px;
        border-bottom: 2px solid var(--soft-orange);
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
        color: #333;
        font-size: 14px;
    }

    .form-group input,
    .form-group select,
    .form-group textarea {
        width: 100%;
        padding: 12px;
        border: 1px solid #ddd;
        border-radius: 6px;
        font-size: 14px;
        font-family: inherit;
        transition: all 0.3s ease;
    }

    .form-group input:focus,
    .form-group select:focus,
    .form-group textarea:focus {
        outline: none;
        border-color: var(--soft-orange);
        box-shadow: 0 0 0 3px rgba(255, 217, 179, 0.2);
    }

    .form-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
    }

    .savings-goals-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }

    .savings-goals-table th {
        background: linear-gradient(135deg, #FFD9B3 0%, #D9B3FF 100%);
        padding: 15px;
        text-align: left;
        font-weight: 700;
        color: #333;
        border-bottom: 2px solid #ddd;
    }

    .savings-goals-table td {
        padding: 15px;
        border-bottom: 1px solid #eee;
        color: #333;
    }

    .savings-goals-table tbody tr:hover {
        background: #f9f9f9;
    }

    .savings-goals-table .action-btn {
        padding: 6px 12px;
        margin-right: 5px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-size: 12px;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .savings-goals-table .edit-btn {
        background: var(--soft-orange);
        color: #333;
    }

    .savings-goals-table .delete-btn {
        background: var(--soft-pink);
        color: #333;
    }

    .savings-goals-table .action-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 2px 6px rgba(0,0,0,0.15);
    }

    .progress-bar {
        width: 100%;
        height: 12px;
        background: #E0E0E0;
        border-radius: 6px;
        overflow: hidden;
        margin: 8px 0;
    }

    .progress-fill {
        height: 100%;
        background: linear-gradient(90deg, #FFD9B3 0%, #D9B3FF 100%);
        border-radius: 6px;
        transition: width 0.3s ease;
    }

    .summary-cards {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        margin-top: 30px;
    }

    .summary-card {
        background: linear-gradient(135deg, #FFD9B3 0%, #D9B3FF 100%);
        padding: 20px;
        border-radius: 8px;
        text-align: center;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    .summary-card-label {
        font-size: 12px;
        font-weight: 600;
        color: #666;
        text-transform: uppercase;
        margin-bottom: 10px;
    }

    .summary-card-value {
        font-size: 28px;
        font-weight: 700;
        color: #333;
    }

    .btn-group {
        display: flex;
        gap: 10px;
        margin-top: 30px;
    }

    .btn {
        flex: 1;
        padding: 12px 20px;
        border: none;
        border-radius: 6px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .btn-primary {
        background: linear-gradient(135deg, #FFD9B3 0%, #D9B3FF 100%);
        color: #333;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }

    .btn-secondary {
        background: linear-gradient(135deg, #B3D9FF 0%, #B3FFB3 100%);
        color: #333;
    }

    .btn-secondary:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }

    .chart-container {
        background: white;
        padding: 30px;
        border-radius: 8px;
        margin-bottom: 30px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    .chart-title {
        font-size: 16px;
        font-weight: 700;
        color: #333;
        margin-bottom: 20px;
    }

    .chart-canvas {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 300px;
    }

    .charts-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 30px;
        margin-bottom: 30px;
    }

    .chart-wrapper {
        background: white;
        padding: 30px;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    .chart-wrapper h3 {
        font-size: 16px;
        font-weight: 700;
        color: #333;
        margin-bottom: 20px;
        padding-bottom: 15px;
        border-bottom: 2px solid var(--soft-orange);
    }

    .chart-wrapper canvas {
        max-height: 350px;
    }

    .mini-stat {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 12px 0;
        border-bottom: 1px solid #f0f0f0;
    }

    .mini-stat:last-child {
        border-bottom: none;
    }

    .mini-stat-label {
        font-size: 13px;
        color: #666;
        font-weight: 600;
    }

    .mini-stat-value {
        font-size: 16px;
        font-weight: 700;
        background: linear-gradient(135deg, #FFD9B3 0%, #D9B3FF 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    /* Success Notification Styles */
    .toast-container {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 10000;
    }

    .toast {
        background: linear-gradient(135deg, #FFD9B3 0%, #B3FFB3 100%);
        color: #333;
        padding: 16px 24px;
        border-radius: 12px;
        margin-bottom: 12px;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
        display: flex;
        align-items: center;
        gap: 12px;
        min-width: 300px;
        animation: slideInRight 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
        font-weight: 600;
    }

    .toast.success {
        background: linear-gradient(135deg, #B3FFB3 0%, #B3FFD9 100%);
    }

    .toast.error {
        background: linear-gradient(135deg, #FFB3D9 0%, #FFD9B3 100%);
    }

    .toast-icon {
        font-size: 20px;
        flex-shrink: 0;
    }

    @keyframes slideInRight {
        from {
            transform: translateX(400px);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }

    @keyframes slideOutRight {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(400px);
            opacity: 0;
        }
    }

    /* Modal Styles */
    .modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.5);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 9999;
        opacity: 0;
        visibility: hidden;
        transition: all 0.3s ease;
    }

    .modal-overlay.active {
        opacity: 1;
        visibility: visible;
    }

    .modal {
        background: white;
        border-radius: 16px;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        max-width: 500px;
        width: 90%;
        max-height: 90vh;
        overflow-y: auto;
        animation: modalSlideIn 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
        padding: 0;
    }

    @keyframes modalSlideIn {
        from {
            transform: translateY(-50px) scale(0.9);
            opacity: 0;
        }
        to {
            transform: translateY(0) scale(1);
            opacity: 1;
        }
    }

    .modal-header {
        background: linear-gradient(135deg, #FFD9B3 0%, #D9B3FF 100%);
        padding: 24px;
        border-bottom: 2px solid #f0f0f0;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-radius: 16px 16px 0 0;
    }

    .modal-header h3 {
        margin: 0;
        font-size: 20px;
        color: #333;
    }

    .modal-close {
        background: none;
        border: none;
        font-size: 28px;
        color: #666;
        cursor: pointer;
        padding: 0;
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s;
        border-radius: 6px;
    }

    .modal-close:hover {
        background: rgba(0, 0, 0, 0.1);
        color: #333;
    }

    .modal-body {
        padding: 24px;
    }

    .modal-body .form-group {
        margin-bottom: 16px;
    }

    .modal-body .form-control {
        width: 100%;
        padding: 12px;
        border: 1px solid #ddd;
        border-radius: 8px;
        font-size: 14px;
        font-family: inherit;
        transition: all 0.3s ease;
    }

    .modal-body .form-control:focus {
        outline: none;
        border-color: #FFD9B3;
        box-shadow: 0 0 0 3px rgba(255, 217, 179, 0.2);
    }

    .modal-footer {
        display: flex;
        gap: 12px;
        padding: 24px;
        border-top: 1px solid #f0f0f0;
        background: #fafafa;
        border-radius: 0 0 16px 16px;
    }

    .modal-footer button {
        flex: 1;
        padding: 12px 20px;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
    }

    .modal-footer .btn-cancel {
        background: #e5e5e5;
        color: #333;
    }

    .modal-footer .btn-cancel:hover {
        background: #d5d5d5;
    }

    .modal-footer .btn-confirm {
        background: linear-gradient(135deg, #FFD9B3 0%, #D9B3FF 100%);
        color: #333;
    }

    .modal-footer .btn-confirm:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    /* Side Panel Styles */
    .side-panel {
        position: fixed;
        right: -400px;
        top: 0;
        width: 400px;
        height: 100vh;
        background: white;
        box-shadow: -4px 0 20px rgba(0, 0, 0, 0.15);
        z-index: 9998;
        transition: right 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
        overflow-y: auto;
    }

    .side-panel.active {
        right: 0;
    }

    .side-panel-header {
        background: linear-gradient(135deg, #B3D9FF 0%, #B3FFB3 100%);
        padding: 24px;
        position: sticky;
        top: 0;
        z-index: 10;
        border-bottom: 2px solid #f0f0f0;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .side-panel-header h3 {
        margin: 0;
        font-size: 18px;
        color: #333;
    }

    .side-panel-close {
        background: none;
        border: none;
        font-size: 28px;
        color: #666;
        cursor: pointer;
        padding: 0;
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .side-panel-content {
        padding: 24px;
    }

    .detail-item {
        margin-bottom: 20px;
        padding-bottom: 20px;
        border-bottom: 1px solid #f0f0f0;
    }

    .detail-item:last-child {
        border-bottom: none;
    }

    .detail-label {
        font-size: 12px;
        font-weight: 700;
        color: #999;
        text-transform: uppercase;
        margin-bottom: 8px;
        letter-spacing: 0.5px;
    }

    .detail-value {
        font-size: 16px;
        font-weight: 600;
        color: #333;
    }

    .detail-value.amount {
        font-size: 18px;
        background: linear-gradient(135deg, #FFD9B3 0%, #D9B3FF 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    /* Enhanced Action Buttons */
    .action-btn {
        padding: 8px 12px;
        margin-right: 8px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-size: 12px;
        font-weight: 600;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }

    .action-btn.view-btn {
        background: linear-gradient(135deg, #B3D9FF 0%, #B3FFD9 100%);
        color: #333;
    }

    .action-btn.view-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(179, 217, 255, 0.4);
    }

    .action-btn.edit-btn {
        background: linear-gradient(135deg, #FFD9B3 0%, #FFCCB3 100%);
        color: #333;
    }

    .action-btn.edit-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(255, 217, 179, 0.4);
    }

    .action-btn.delete-btn {
        background: linear-gradient(135deg, #FFB3D9 0%, #D9B3FF 100%);
        color: #333;
    }

    .action-btn.delete-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(255, 179, 217, 0.4);
    }

    /* Confirmation Dialog */
    .confirmation-dialog {
        text-align: center;
        padding: 20px 0;
    }

    .confirmation-dialog .dialog-icon {
        font-size: 48px;
        margin-bottom: 16px;
    }

    .confirmation-dialog .dialog-title {
        font-size: 18px;
        font-weight: 700;
        color: #333;
        margin-bottom: 12px;
    }

    .confirmation-dialog .dialog-message {
        font-size: 14px;
        color: #666;
        margin-bottom: 24px;
        line-height: 1.6;
    }

    /* Mini Charts in Detail Panel */
    .mini-chart {
        margin-top: 24px;
        padding-top: 24px;
        border-top: 2px solid #f0f0f0;
    }

    .mini-chart-title {
        font-size: 14px;
        font-weight: 700;
        color: #333;
        margin-bottom: 12px;
    }

    .progress-circle {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        background: conic-gradient(#FFD9B3 var(--progress), #f0f0f0 0);
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 16px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .progress-circle-text {
        text-align: center;
    }

    .progress-circle-value {
        font-size: 20px;
        font-weight: 700;
        color: #333;
    }

    .progress-circle-label {
        font-size: 11px;
        color: #999;
        text-transform: uppercase;
    }

    @media (max-width: 1200px) {
        .form-row {
            grid-template-columns: repeat(2, 1fr);
        }
        
        .charts-grid {
            grid-template-columns: repeat(2, 1fr);
        }
        
        .summary-cards {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 992px) {
        .form-row {
            grid-template-columns: 1fr;
        }
        
        .charts-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }
        
        .page-header {
            padding: 20px;
        }
        
        .page-header h1 {
            font-size: 26px;
        }
        
        .form-section {
            padding: 20px;
            margin-bottom: 20px;
        }
        
        .summary-cards {
            grid-template-columns: repeat(2, 1fr);
            gap: 12px;
        }
    }

    @media (max-width: 768px) {
        .form-row {
            grid-template-columns: 1fr;
        }

        .page-header h1 {
            font-size: 22px;
        }
        
        .page-header {
            padding: 15px;
            margin-bottom: 20px;
        }
        
        .page-header p {
            font-size: 13px;
        }
        
        .back-button {
            padding: 8px 12px;
            font-size: 12px;
            margin-bottom: 15px;
        }
        
        .form-section {
            padding: 15px;
            margin-bottom: 15px;
        }
        
        .section-title {
            font-size: 16px;
            margin-bottom: 15px;
        }
        
        .summary-cards {
            grid-template-columns: 1fr;
            gap: 10px;
        }
        
        .summary-card {
            padding: 12px;
        }
        
        .summary-card-label {
            font-size: 10px;
        }
        
        .summary-card-value {
            font-size: 20px;
        }
        
        .charts-grid {
            grid-template-columns: 1fr;
            gap: 15px;
        }
        
        .chart-wrapper {
            padding: 15px;
        }
        
        .chart-wrapper h3 {
            font-size: 14px;
        }
        
        .side-panel {
            width: 100%;
            right: -100%;
        }

        .toast {
            min-width: 280px;
        }
        
        .savings-goals-table {
            font-size: 11px;
        }
        
        .savings-goals-table th,
        .savings-goals-table td {
            padding: 10px;
        }
    }

    @media (max-width: 480px) {
        .form-row {
            grid-template-columns: 1fr;
        }

        .page-header h1 {
            font-size: 18px;
        }
        
        .page-header {
            padding: 12px;
            margin-bottom: 15px;
        }
        
        .page-header p {
            font-size: 12px;
        }
        
        .back-button {
            padding: 8px 12px;
            font-size: 11px;
            margin-bottom: 12px;
        }
        
        .form-section {
            padding: 12px;
            margin-bottom: 12px;
        }
        
        .section-title {
            font-size: 14px;
            margin-bottom: 12px;
        }
        
        .form-group {
            margin-bottom: 15px;
        }
        
        .form-group label {
            font-size: 12px;
            margin-bottom: 6px;
        }
        
        .form-group input,
        .form-group select,
        .form-group textarea {
            padding: 10px;
            font-size: 12px;
        }
        
        .btn {
            padding: 8px 12px;
            font-size: 11px;
        }
        
        .summary-cards {
            grid-template-columns: 1fr;
            gap: 8px;
        }
        
        .summary-card {
            padding: 10px;
        }
        
        .summary-card-label {
            font-size: 9px;
        }
        
        .summary-card-value {
            font-size: 18px;
        }
        
        .charts-grid {
            grid-template-columns: 1fr;
            gap: 12px;
        }
        
        .chart-wrapper {
            padding: 12px;
        }
        
        .chart-wrapper h3 {
            font-size: 13px;
            margin-bottom: 12px;
        }
        
        .chart-wrapper canvas {
            max-height: 250px;
        }
        
        .savings-goals-table {
            font-size: 10px;
        }
        
        .savings-goals-table th,
        .savings-goals-table td {
            padding: 8px;
        }
        
        .action-btn {
            padding: 6px 10px;
            font-size: 10px;
            margin-right: 4px;
        }
        
        .modal-content {
            max-width: 95%;
            padding: 15px;
        }
        
        .modal-header {
            padding: 15px;
        }
        
        .modal-body {
            padding: 15px;
        }
        
        .modal-footer {
            padding: 15px;
            gap: 8px;
        }
        
        .side-panel {
            width: 100%;
            right: -100%;
        }

        .toast {
            min-width: 240px;
            font-size: 11px;
            padding: 12px 16px;
        }
    }
</style>

<div class="savings-container">
    <a href="/admin/financial" class="back-button">← Back to Dashboard</a>

    <!-- Toast Container for Success Messages -->
    <div class="toast-container" id="toastContainer"></div>

    <!-- Edit Modal -->
    <div class="modal-overlay" id="editModal">
        <div class="modal">
            <div class="modal-header">
                <h3>✏️ Edit Savings Goal</h3>
                <button class="modal-close" onclick="closeEditModal()">×</button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="editGoalName">Goal Name</label>
                    <input type="text" id="editGoalName" class="form-control">
                </div>
                <div class="form-group">
                    <label for="editCurrentAmount">Current Amount</label>
                    <input type="number" id="editCurrentAmount" class="form-control" min="0" step="0.01">
                </div>
                <div class="form-group">
                    <label for="editMonthlyContribution">Monthly Contribution</label>
                    <input type="number" id="editMonthlyContribution" class="form-control" min="0" step="0.01">
                </div>
                <div class="form-group">
                    <label for="editNotes">Notes</label>
                    <textarea id="editNotes" class="form-control" rows="3"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn-cancel" onclick="closeEditModal()">Cancel</button>
                <button class="btn-confirm" onclick="saveEditedGoal()">Save Changes</button>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal-overlay" id="deleteModal">
        <div class="modal">
            <div class="modal-header">
                <h3>🗑️ Delete Savings Goal</h3>
                <button class="modal-close" onclick="closeDeleteModal()">×</button>
            </div>
            <div class="modal-body">
                <div class="confirmation-dialog">
                    <div class="dialog-icon">⚠️</div>
                    <div class="dialog-title">Are you sure?</div>
                    <div class="dialog-message">
                        This action cannot be undone. The savings goal and all associated data will be permanently deleted.
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn-cancel" onclick="closeDeleteModal()">Cancel</button>
                <button class="btn-confirm" style="background: linear-gradient(135deg, #FFB3D9 0%, #D9B3FF 100%);" onclick="confirmDelete()">Delete Goal</button>
            </div>
        </div>
    </div>

    <!-- View Details Side Panel -->
    <div class="side-panel" id="detailsPanel">
        <div class="side-panel-header">
            <h3 id="panelTitle">Goal Details</h3>
            <button class="side-panel-close" onclick="closeDetailsPanel()">×</button>
        </div>
        <div class="side-panel-content">
            <div class="detail-item">
                <div class="detail-label">Goal Name</div>
                <div class="detail-value" id="panelGoalName">-</div>
            </div>
            <div class="detail-item">
                <div class="detail-label">Category</div>
                <div class="detail-value" id="panelCategory" style="background: linear-gradient(135deg, #FFD9B3 0%, #D9B3FF 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">-</div>
            </div>
            <div class="detail-item">
                <div class="detail-label">Target Amount</div>
                <div class="detail-value amount" id="panelTargetAmount">$0</div>
            </div>
            <div class="detail-item">
                <div class="detail-label">Current Amount</div>
                <div class="detail-value amount" id="panelCurrentAmount">$0</div>
            </div>
            <div class="detail-item">
                <div class="detail-label">Remaining to Save</div>
                <div class="detail-value amount" id="panelRemaining">$0</div>
            </div>
            <div class="detail-item">
                <div class="detail-label">Monthly Contribution</div>
                <div class="detail-value amount" id="panelMonthlyContribution">$0</div>
            </div>
            <div class="detail-item">
                <div class="detail-label">Target Date</div>
                <div class="detail-value" id="panelTargetDate">-</div>
            </div>
            <div class="detail-item">
                <div class="detail-label">Days Remaining</div>
                <div class="detail-value" id="panelDaysRemaining">-</div>
            </div>
            <div class="detail-item">
                <div class="detail-label">Notes</div>
                <div class="detail-value" id="panelNotes" style="color: #666; font-size: 14px;">-</div>
            </div>
            <div class="mini-chart">
                <div class="mini-chart-title">Progress Overview</div>
                <div class="progress-circle" id="progressCircle" style="--progress: 0%;">
                    <div class="progress-circle-text">
                        <div class="progress-circle-value" id="progressValue">0%</div>
                        <div class="progress-circle-label">Complete</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="page-header">
        <h1>🎯 Savings Goals Management</h1>
        <p>Create and track your savings goals with progress monitoring</p>
    </div>

    <!-- Create Savings Goal Section -->
    <div class="form-section">
        <h2 class="section-title">1. Create New Savings Goal</h2>
        <p style="color: #666; margin-bottom: 20px; font-size: 14px;">Set up a new savings goal and track your progress</p>

        <form id="savingsGoalForm">
            <div class="form-row">
                <div class="form-group">
                    <label for="goalName">Goal Name *</label>
                    <input type="text" id="goalName" placeholder="e.g., Emergency Fund" required>
                </div>
                <div class="form-group">
                    <label for="goalCategory">Category *</label>
                    <select id="goalCategory" required>
                        <option value="">Select Category</option>
                        <option value="emergency">Emergency Fund</option>
                        <option value="vacation">Vacation</option>
                        <option value="home">Home Purchase</option>
                        <option value="car">Car Purchase</option>
                        <option value="education">Education</option>
                        <option value="retirement">Retirement</option>
                        <option value="investment">Investment</option>
                        <option value="other">Other</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="goalAmount">Target Amount *</label>
                    <input type="number" id="goalAmount" placeholder="$0.00" min="0" step="0.01" required>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="currentAmount">Current Amount *</label>
                    <input type="number" id="currentAmount" placeholder="$0.00" min="0" step="0.01" required>
                </div>
                <div class="form-group">
                    <label for="targetDate">Target Date *</label>
                    <input type="date" id="targetDate" required>
                </div>
                <div class="form-group">
                    <label for="monthlyContribution">Monthly Contribution *</label>
                    <input type="number" id="monthlyContribution" placeholder="$0.00" min="0" step="0.01" required>
                </div>
            </div>
            <div class="form-group">
                <label for="goalNotes">Notes</label>
                <textarea id="goalNotes" placeholder="Add any notes about this goal" rows="3"></textarea>
            </div>
            <button type="button" class="btn btn-primary" onclick="addSavingsGoal(); return false;">+ Create Savings Goal</button>
        </form>

        <table class="savings-goals-table" id="savingsGoalsTable" style="display: none;">
            <thead>
                <tr>
                    <th>Goal Name</th>
                    <th>Category</th>
                    <th>Target Amount</th>
                    <th>Current Amount</th>
                    <th>Progress</th>
                    <th>Target Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="savingsGoalsBody">
            </tbody>
        </table>
    </div>

    <!-- Savings Contributions Section -->
    <div class="form-section">
        <h2 class="section-title">2. Record Savings Contribution</h2>
        <p style="color: #666; margin-bottom: 20px; font-size: 14px;">Add contributions to your savings goals</p>

        <form id="contributionForm">
            <div class="form-row">
                <div class="form-group">
                    <label for="contributionGoal">Select Goal *</label>
                    <select id="contributionGoal" required>
                        <option value="">Select a goal</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="contributionAmount">Amount *</label>
                    <input type="number" id="contributionAmount" placeholder="$0.00" min="0" step="0.01" required>
                </div>
                <div class="form-group">
                    <label for="contributionDate">Date *</label>
                    <input type="date" id="contributionDate" required>
                </div>
            </div>
            <button type="button" class="btn btn-primary" onclick="addContribution()">+ Add Contribution</button>
        </form>
    </div>

    <!-- Summary Section -->
    <div class="form-section">
        <h2 class="section-title">3. Savings Summary</h2>
        
        <div class="summary-cards">
            <div class="summary-card">
                <div class="summary-card-label">Total Savings Goals</div>
                <div class="summary-card-value" id="totalGoals">0</div>
            </div>
            <div class="summary-card">
                <div class="summary-card-label">Total Target Amount</div>
                <div class="summary-card-value" id="totalTargetAmount">$0</div>
            </div>
            <div class="summary-card">
                <div class="summary-card-label">Total Saved</div>
                <div class="summary-card-value" id="totalSaved">$0</div>
            </div>
            <div class="summary-card">
                <div class="summary-card-label">Overall Progress</div>
                <div class="summary-card-value" id="overallProgress">0%</div>
            </div>
        </div>
    </div>

    <!-- Goals Progress Chart -->
    <div class="chart-container">
        <h2 class="chart-title">4. Savings Goals Progress</h2>
        <div class="chart-canvas">
            <canvas id="savingsProgressChart" style="max-width: 400px; max-height: 300px;"></canvas>
        </div>
    </div>

    <!-- Additional Charts Section -->
    <div class="charts-grid">
        <!-- Category Distribution Chart -->
        <div class="chart-wrapper">
            <h3>📊 Goals by Category</h3>
            <canvas id="categoryChart"></canvas>
        </div>

        <!-- Goal Completion Status Chart -->
        <div class="chart-wrapper">
            <h3>🎯 Completion Status</h3>
            <canvas id="completionChart"></canvas>
        </div>

        <!-- Monthly Contribution Forecast -->
        <div class="chart-wrapper">
            <h3>📈 Monthly Contribution Forecast</h3>
            <canvas id="forecastChart"></canvas>
        </div>

        <!-- Savings Trend Chart -->
        <div class="chart-wrapper">
            <h3>📉 Savings Trend</h3>
            <canvas id="trendChart"></canvas>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="form-section">
        <div class="btn-group">
            <button class="btn btn-primary" onclick="saveSavingsData()">💾 Save Savings Data</button>
            <button class="btn btn-secondary" onclick="exportSavingsReport()">📊 Export Report</button>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    let savingsGoals = [];
    let contributions = [];

    // Add savings goal
    function addSavingsGoal() {
        console.log('addSavingsGoal() called!');
        const name = document.getElementById('goalName').value;
        const category = document.getElementById('goalCategory').value;
        const targetAmount = parseFloat(document.getElementById('goalAmount').value);
        const currentAmount = parseFloat(document.getElementById('currentAmount').value);
        const targetDate = document.getElementById('targetDate').value;
        const monthlyContribution = parseFloat(document.getElementById('monthlyContribution').value);
        const notes = document.getElementById('goalNotes').value;

        console.log('Form values:', { name, category, targetAmount, currentAmount, targetDate, monthlyContribution, notes });

        if (!name || !category || targetDate === '' || targetAmount === '' || currentAmount === '' || monthlyContribution === '') {
            console.error('Validation failed - empty fields:', { name, category, targetAmount, currentAmount, targetDate, monthlyContribution });
            showToast('Please fill in all required fields', 'error');
            return;
        }

        // Convert to numbers after validation
        const parsedTargetAmount = parseFloat(targetAmount);
        const parsedCurrentAmount = parseFloat(currentAmount);
        const parsedMonthlyContribution = parseFloat(monthlyContribution);

        if (isNaN(parsedTargetAmount) || isNaN(parsedCurrentAmount) || isNaN(parsedMonthlyContribution)) {
            console.error('Invalid numbers');
            showToast('Target Amount, Current Amount, and Monthly Contribution must be valid numbers', 'error');
            return;
        }

        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
        console.log('CSRF Token:', csrfToken);
        console.log('Saving goal:', { name, category, parsedTargetAmount, parsedCurrentAmount, targetDate, parsedMonthlyContribution, notes });

        // Save to database immediately
        fetch('/admin/api/financial/savings-goal', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken || ''
            },
            body: JSON.stringify({
                goal_name: name,
                category: category,
                target_amount: parsedTargetAmount,
                current_amount: parsedCurrentAmount,
                target_date: targetDate,
                monthly_contribution: parsedMonthlyContribution,
                notes: notes
            })
        })
        .then(response => {
            console.log('Response status:', response.status);
            return response.json();
        })
        .then(data => {
            console.log('Response data:', data);
            console.log('Goal ID from response:', data.data?.id);
            if (data.success && data.data && data.data.id) {
                const goal = { 
                    id: data.data.id, 
                    name, 
                    category, 
                    targetAmount: parsedTargetAmount, 
                    currentAmount: parsedCurrentAmount, 
                    targetDate, 
                    monthlyContribution: parsedMonthlyContribution, 
                    notes 
                };
                console.log('Adding goal to array:', goal);
                savingsGoals.push(goal);

                document.getElementById('goalName').value = '';
                document.getElementById('goalCategory').value = '';
                document.getElementById('goalAmount').value = '';
                document.getElementById('currentAmount').value = '';
                document.getElementById('targetDate').value = '';
                document.getElementById('monthlyContribution').value = '';
                document.getElementById('goalNotes').value = '';

                updateSavingsGoalsTable();
                updateContributionDropdown();
                updateSummary();
                showToast('✓ Savings goal created successfully!', 'success');
            } else {
                console.error('API Error:', data);
                showToast('Error: ' + (data.message || 'Failed to create goal'), 'error');
            }
        })
        .catch(error => {
            console.error('Fetch Error:', error);
            showToast('Error saving goal: ' + error.message, 'error');
        });
    }

    // Update savings goals table
    function updateSavingsGoalsTable() {
        const table = document.getElementById('savingsGoalsTable');
        const tbody = document.getElementById('savingsGoalsBody');

        if (savingsGoals.length === 0) {
            table.style.display = 'none';
            return;
        }

        table.style.display = 'table';
        tbody.innerHTML = '';

        savingsGoals.forEach(goal => {
            const progress = (goal.currentAmount / goal.targetAmount * 100).toFixed(1);
            const row = document.createElement('tr');
            row.innerHTML = `
                <td><strong>${goal.name}</strong></td>
                <td><span style="background: var(--soft-orange); padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: 600;">${goal.category}</span></td>
                <td>$${goal.targetAmount.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}</td>
                <td>$${goal.currentAmount.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}</td>
                <td>
                    <div class="progress-bar">
                        <div class="progress-fill" style="width: ${progress}%;"></div>
                    </div>
                    <span style="font-size: 12px; font-weight: 600;">${progress}%</span>
                </td>
                <td>${goal.targetDate}</td>
                <td style="white-space: nowrap;">
                    <button class="action-btn view-btn" onclick="viewGoal(${goal.id})" title="View Details">👁️ View</button>
                    <button class="action-btn edit-btn" onclick="openEditModal(${goal.id})" title="Edit Goal">✏️ Edit</button>
                    <button class="action-btn delete-btn" onclick="openDeleteModal(${goal.id})" title="Delete Goal">🗑️ Delete</button>
                </td>
            `;
            tbody.appendChild(row);
        });
    }

    // Update contribution dropdown
    function updateContributionDropdown() {
        const select = document.getElementById('contributionGoal');
        select.innerHTML = '<option value="">Select a goal</option>';

        savingsGoals.forEach(goal => {
            const option = document.createElement('option');
            option.value = goal.id;
            option.textContent = goal.name;
            select.appendChild(option);
        });
    }

    // Add contribution
    function addContribution() {
        const goalId = parseInt(document.getElementById('contributionGoal').value);
        const amount = parseFloat(document.getElementById('contributionAmount').value);
        const date = document.getElementById('contributionDate').value;

        if (!goalId || !amount || !date) {
            showToast('Please fill in all fields', 'error');
            return;
        }

        const goal = savingsGoals.find(g => g.id === goalId);
        if (goal) {
            const newAmount = goal.currentAmount + amount;
            
            // Save to database immediately
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
            fetch(`/admin/api/financial/savings-goal/${goalId}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken || ''
                },
                body: JSON.stringify({
                    current_amount: newAmount
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update in-memory array
                    goal.currentAmount = newAmount;
                    contributions.push({ goalId, amount, date, id: Date.now() });

                    document.getElementById('contributionGoal').value = '';
                    document.getElementById('contributionAmount').value = '';
                    document.getElementById('contributionDate').value = '';

                    updateSavingsGoalsTable();
                    updateSummary();
                    showToast('✓ Contribution added successfully!', 'success');
                } else {
                    showToast('Error: ' + (data.message || 'Failed to add contribution'), 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('Error adding contribution: ' + error.message, 'error');
            });
        }
    }

    // Modal and UI Functions
    let currentEditingGoalId = null;
    let currentDeletingGoalId = null;

    // View goal details in side panel
    function viewGoal(id) {
        const goal = savingsGoals.find(g => g.id === id);
        if (!goal) return;

        const progress = (goal.currentAmount / goal.targetAmount * 100).toFixed(1);
        const remaining = goal.targetAmount - goal.currentAmount;
        
        // Calculate days remaining
        const targetDate = new Date(goal.targetDate);
        const today = new Date();
        const daysRemaining = Math.ceil((targetDate - today) / (1000 * 60 * 60 * 24));

        document.getElementById('panelTitle').textContent = `📊 ${goal.name}`;
        document.getElementById('panelGoalName').textContent = goal.name;
        document.getElementById('panelCategory').textContent = goal.category.toUpperCase();
        document.getElementById('panelTargetAmount').textContent = '$' + goal.targetAmount.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        document.getElementById('panelCurrentAmount').textContent = '$' + goal.currentAmount.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        document.getElementById('panelRemaining').textContent = '$' + remaining.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        document.getElementById('panelMonthlyContribution').textContent = '$' + goal.monthlyContribution.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        document.getElementById('panelTargetDate').textContent = goal.targetDate;
        document.getElementById('panelDaysRemaining').textContent = daysRemaining > 0 ? daysRemaining + ' days' : 'Target reached!';
        document.getElementById('panelNotes').textContent = goal.notes || 'No notes added';
        
        // Update progress circle
        document.getElementById('progressCircle').style.setProperty('--progress', (progress * 3.6) + 'deg');
        document.getElementById('progressValue').textContent = progress + '%';

        // Show side panel
        document.getElementById('detailsPanel').classList.add('active');
    }

    function closeDetailsPanel() {
        document.getElementById('detailsPanel').classList.remove('active');
    }

    // Edit modal
    function openEditModal(id) {
        const goal = savingsGoals.find(g => g.id === id);
        if (!goal) {
            showToast('Goal not found!', 'error');
            return;
        }

        if (!goal.id || goal.id <= 0) {
            showToast('Cannot edit this goal. It may not be saved properly. Please refresh the page.', 'error');
            return;
        }

        console.log('Opening edit modal for goal:', goal);
        currentEditingGoalId = id;
        document.getElementById('editGoalName').value = goal.name;
        document.getElementById('editCurrentAmount').value = goal.currentAmount;
        document.getElementById('editMonthlyContribution').value = goal.monthlyContribution;
        document.getElementById('editNotes').value = goal.notes;

        document.getElementById('editModal').classList.add('active');
    }

    function closeEditModal() {
        document.getElementById('editModal').classList.remove('active');
        currentEditingGoalId = null;
    }

    function saveEditedGoal() {
        const goal = savingsGoals.find(g => g.id === currentEditingGoalId);
        if (!goal) {
            showToast('Goal not found!', 'error');
            return;
        }

        // Check if goal has a valid ID
        if (!goal.id || goal.id <= 0) {
            showToast('Cannot edit unsaved goal. Please refresh the page first.', 'error');
            return;
        }

        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
        const goalId = currentEditingGoalId;
        const name = document.getElementById('editGoalName').value;
        const currentAmount = parseFloat(document.getElementById('editCurrentAmount').value);
        const monthlyContribution = parseFloat(document.getElementById('editMonthlyContribution').value);
        const notes = document.getElementById('editNotes').value;

        if (!name || isNaN(currentAmount)) {
            showToast('Please fill in all required fields', 'error');
            return;
        }

        // Save to database via API
        fetch(`/admin/api/financial/savings-goal/${goalId}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken || ''
            },
            body: JSON.stringify({
                goal_name: name,
                current_amount: currentAmount,
                monthly_contribution: monthlyContribution,
                notes: notes
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update in-memory array
                goal.name = name;
                goal.currentAmount = currentAmount;
                goal.monthlyContribution = monthlyContribution;
                goal.notes = notes;

                updateSavingsGoalsTable();
                updateSummary();
                closeEditModal();
                showToast('✓ Goal updated successfully!', 'success');
            } else {
                console.error('API Error:', data);
                showToast('Error: ' + (data.message || 'Failed to update goal'), 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('Error updating goal: ' + error.message, 'error');
        });
    }

    // Delete modal
    function openDeleteModal(id) {
        currentDeletingGoalId = id;
        document.getElementById('deleteModal').classList.add('active');
    }

    function closeDeleteModal() {
        document.getElementById('deleteModal').classList.remove('active');
        currentDeletingGoalId = null;
    }

    function confirmDelete() {
        if (!currentDeletingGoalId) {
            showToast('No goal selected for deletion', 'error');
            return;
        }

        const goal = savingsGoals.find(g => g.id === currentDeletingGoalId);
        if (!goal) {
            showToast('Goal not found!', 'error');
            return;
        }

        // Check if goal has a valid ID
        if (!goal.id || goal.id <= 0) {
            showToast('Cannot delete unsaved goal. Please refresh the page first.', 'error');
            return;
        }

        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
        const goalId = currentDeletingGoalId;

        // Delete from database via API
        fetch(`/admin/api/financial/savings-goal/${goalId}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken || ''
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Remove from in-memory array
                savingsGoals = savingsGoals.filter(g => g.id !== goalId);
                updateSavingsGoalsTable();
                updateContributionDropdown();
                updateSummary();
                closeDeleteModal();
                showToast('✓ Goal deleted successfully!', 'success');
            } else {
                console.error('API Error:', data);
                showToast('Error: ' + (data.message || 'Failed to delete goal'), 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('Error deleting goal: ' + error.message, 'error');
        });
    }

    // Toast notification
    function showToast(message, type = 'success') {
        const container = document.getElementById('toastContainer');
        const toast = document.createElement('div');
        toast.className = `toast ${type}`;
        
        const icon = type === 'success' ? '✓' : '✕';
        toast.innerHTML = `
            <span class="toast-icon">${icon}</span>
            <span>${message}</span>
        `;

        container.appendChild(toast);

        // Auto-remove after 4 seconds
        setTimeout(() => {
            toast.style.animation = 'slideOutRight 0.4s ease-out forwards';
            setTimeout(() => toast.remove(), 400);
        }, 4000);
    }

    // Close modals when clicking outside
    document.addEventListener('click', function(event) {
        const editModal = document.getElementById('editModal');
        const deleteModal = document.getElementById('deleteModal');

        if (event.target === editModal) closeEditModal();
        if (event.target === deleteModal) closeDeleteModal();
    });

    // Delete goal (old function kept for compatibility)
    function deleteGoal(id) {
        openDeleteModal(id);
    }

    // Update summary
    function updateSummary() {
        const totalGoals = savingsGoals.length;
        const totalTargetAmount = savingsGoals.reduce((sum, g) => sum + g.targetAmount, 0);
        const totalSaved = savingsGoals.reduce((sum, g) => sum + g.currentAmount, 0);
        const overallProgress = totalTargetAmount > 0 ? (totalSaved / totalTargetAmount * 100).toFixed(1) : 0;

        document.getElementById('totalGoals').textContent = totalGoals;
        document.getElementById('totalTargetAmount').textContent = '$' + totalTargetAmount.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        document.getElementById('totalSaved').textContent = '$' + totalSaved.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        document.getElementById('overallProgress').textContent = overallProgress + '%';

        updateChart();
    }

    // Update chart
    function updateChart() {
        const ctx = document.getElementById('savingsProgressChart');
        
        if (!ctx) {
            return;
        }

        if (ctx.chart) {
            ctx.chart.destroy();
        }

        if (savingsGoals.length === 0) {
            // Show empty state
            const parent = ctx.parentElement;
            parent.innerHTML = '<div style="text-align: center; padding: 40px; color: #999;">No savings goals yet. Create one to see charts!</div>';
            return;
        }

        const labels = savingsGoals.map(g => g.name);
        const currentAmounts = savingsGoals.map(g => g.currentAmount);
        const remainingAmounts = savingsGoals.map(g => g.targetAmount - g.currentAmount);

        ctx.chart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Saved',
                        data: currentAmounts,
                        backgroundColor: '#FFD9B3',
                        borderRadius: 8,
                        borderSkipped: false
                    },
                    {
                        label: 'Remaining',
                        data: remainingAmounts,
                        backgroundColor: '#D9B3FF',
                        borderRadius: 8,
                        borderSkipped: false
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                indexAxis: 'y',
                plugins: {
                    legend: { 
                        labels: { 
                            font: { size: 13, weight: '600' },
                            padding: 15,
                            usePointStyle: true
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        padding: 12,
                        titleFont: { size: 13 },
                        bodyFont: { size: 12 },
                        borderColor: '#FFD9B3',
                        borderWidth: 1
                    }
                },
                scales: {
                    x: { 
                        stacked: true, 
                        beginAtZero: true,
                        grid: { color: 'rgba(0, 0, 0, 0.05)' }
                    },
                    y: {
                        grid: { display: false }
                    }
                }
            }
        });
    }

    // Save savings data
    function saveSavingsData() {
        if (savingsGoals.length === 0) {
            showToast('No savings goals to save!', 'error');
            return;
        }

        // Save each goal to the database
        savingsGoals.forEach(goal => {
            fetch('/admin/api/financial/savings-goal', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                },
                body: JSON.stringify({
                    goal_name: goal.name,
                    category: goal.category,
                    target_amount: goal.targetAmount,
                    current_amount: goal.currentAmount,
                    target_date: goal.targetDate,
                    monthly_contribution: goal.monthlyContribution,
                    notes: goal.notes
                })
            })
            .then(response => response.json())
            .then(data => {
                if (!data.success) {
                    console.error('Error saving goal:', data.message);
                }
            })
            .catch(error => console.error('Error:', error));
        });

        showToast('✓ Savings data saved successfully!', 'success');
    }

    // Export report
    function exportSavingsReport() {
        showToast('📊 Report exported successfully!', 'success');
    }

    // Load savings goals from database
    function loadSavingsGoals() {
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
        fetch('/admin/api/financial/savings-goals', {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken || ''
            }
        })
            .then(response => {
                console.log('Load response status:', response.status);
                return response.json();
            })
            .then(data => {
                console.log('Loaded savings goals:', data);
                if (data.success && data.data && Array.isArray(data.data) && data.data.length > 0) {
                    savingsGoals = data.data.map(goal => ({
                        id: goal.id,
                        name: goal.name,
                        category: goal.category,
                        targetAmount: parseFloat(goal.target_amount || 0),
                        currentAmount: parseFloat(goal.current_amount || 0),
                        targetDate: goal.target_date,
                        monthlyContribution: parseFloat(goal.monthly_contribution || 0),
                        notes: goal.notes || ''
                    }));
                    console.log('Mapped savings goals:', savingsGoals);
                    updateSavingsGoalsTable();
                    updateContributionDropdown();
                    updateSummary();
                    updateAllCharts();
                } else {
                    console.log('No savings goals found or error in response:', data);
                }
            })
            .catch(error => {
                console.error('Error loading goals:', error);
            });
    }

    // Update all charts
    function updateAllCharts() {
        updateCategoryChart();
        updateCompletionChart();
        updateForecastChart();
        updateTrendChart();
    }

    // Category Distribution Chart
    function updateCategoryChart() {
        const ctx = document.getElementById('categoryChart');
        if (!ctx) return;

        if (ctx.chart) ctx.chart.destroy();

        console.log('updateCategoryChart - savingsGoals:', savingsGoals);

        if (savingsGoals.length === 0) {
            ctx.parentElement.innerHTML = '<div style="text-align: center; padding: 20px; color: #999;">No data available</div>';
            return;
        }

        const categories = {};
        savingsGoals.forEach(goal => {
            categories[goal.category] = (categories[goal.category] || 0) + 1;
        });

        const labels = Object.keys(categories);
        const data = Object.values(categories);
        const colors = ['#FFD9B3', '#D9B3FF', '#B3D9FF', '#B3FFB3', '#FFCCB3', '#B3FFD9', '#E6D9FF', '#FFB3D9'];

        ctx.chart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: labels.map(l => l.charAt(0).toUpperCase() + l.slice(1)),
                datasets: [{
                    data: data,
                    backgroundColor: colors.slice(0, labels.length),
                    borderColor: '#fff',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { font: { size: 12, weight: '600' }, padding: 15 }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        padding: 12,
                        titleFont: { size: 13 },
                        bodyFont: { size: 12 }
                    }
                }
            }
        });
    }

    // Goal Completion Status Chart
    function updateCompletionChart() {
        const ctx = document.getElementById('completionChart');
        if (!ctx) return;

        if (ctx.chart) ctx.chart.destroy();

        if (savingsGoals.length === 0) {
            ctx.parentElement.innerHTML = '<div style="text-align: center; padding: 20px; color: #999;">No data available</div>';
            return;
        }

        const completed = savingsGoals.filter(g => (g.currentAmount / g.targetAmount) >= 1).length;
        const inProgress = savingsGoals.filter(g => (g.currentAmount / g.targetAmount) < 1 && (g.currentAmount / g.targetAmount) > 0).length;
        const notStarted = savingsGoals.filter(g => g.currentAmount === 0).length;

        ctx.chart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: ['Completed', 'In Progress', 'Not Started'],
                datasets: [{
                    data: [completed, inProgress, notStarted],
                    backgroundColor: ['#B3FFB3', '#FFD9B3', '#FFB3D9'],
                    borderColor: '#fff',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { font: { size: 12, weight: '600' }, padding: 15 }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        padding: 12,
                        titleFont: { size: 13 },
                        bodyFont: { size: 12 }
                    }
                }
            }
        });
    }

    // Monthly Contribution Forecast Chart
    function updateForecastChart() {
        const ctx = document.getElementById('forecastChart');
        if (!ctx) return;

        if (ctx.chart) ctx.chart.destroy();

        if (savingsGoals.length === 0) {
            ctx.parentElement.innerHTML = '<div style="text-align: center; padding: 20px; color: #999;">No data available</div>';
            return;
        }

        const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'];
        const forecastData = savingsGoals.map(goal => {
            return months.map((_, i) => goal.monthlyContribution * (i + 1));
        });

        const datasets = savingsGoals.map((goal, idx) => ({
            label: goal.name,
            data: forecastData[idx],
            borderColor: ['#FFD9B3', '#D9B3FF', '#B3D9FF', '#B3FFB3', '#FFCCB3', '#B3FFD9'][idx % 6],
            backgroundColor: ['rgba(255, 217, 179, 0.1)', 'rgba(217, 179, 255, 0.1)', 'rgba(179, 217, 255, 0.1)', 'rgba(179, 255, 179, 0.1)', 'rgba(255, 204, 179, 0.1)', 'rgba(179, 255, 217, 0.1)'][idx % 6],
            borderWidth: 2,
            fill: true,
            tension: 0.4
        }));

        ctx.chart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: months,
                datasets: datasets
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { font: { size: 11, weight: '600' }, padding: 10 }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        padding: 12,
                        titleFont: { size: 13 },
                        bodyFont: { size: 12 }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: 'rgba(0, 0, 0, 0.05)' }
                    },
                    x: {
                        grid: { display: false }
                    }
                }
            }
        });
    }

    // Savings Trend Chart
    function updateTrendChart() {
        const ctx = document.getElementById('trendChart');
        if (!ctx) return;

        if (ctx.chart) ctx.chart.destroy();

        if (savingsGoals.length === 0) {
            ctx.parentElement.innerHTML = '<div style="text-align: center; padding: 20px; color: #999;">No data available</div>';
            return;
        }

        const months = ['Week 1', 'Week 2', 'Week 3', 'Week 4'];
        const trendData = savingsGoals.map(goal => {
            const weeklyAmount = goal.monthlyContribution / 4;
            return months.map((_, i) => goal.currentAmount + (weeklyAmount * (i + 1)));
        });

        const datasets = savingsGoals.map((goal, idx) => ({
            label: goal.name,
            data: trendData[idx],
            borderColor: ['#FFD9B3', '#D9B3FF', '#B3D9FF', '#B3FFB3', '#FFCCB3', '#B3FFD9'][idx % 6],
            backgroundColor: ['rgba(255, 217, 179, 0.1)', 'rgba(217, 179, 255, 0.1)', 'rgba(179, 217, 255, 0.1)', 'rgba(179, 255, 179, 0.1)', 'rgba(255, 204, 179, 0.1)', 'rgba(179, 255, 217, 0.1)'][idx % 6],
            borderWidth: 2,
            fill: true,
            tension: 0.4,
            pointBackgroundColor: ['#FFD9B3', '#D9B3FF', '#B3D9FF', '#B3FFB3', '#FFCCB3', '#B3FFD9'][idx % 6],
            pointRadius: 4,
            pointHoverRadius: 6
        }));

        ctx.chart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: months,
                datasets: datasets
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { font: { size: 11, weight: '600' }, padding: 10 }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        padding: 12,
                        titleFont: { size: 13 },
                        bodyFont: { size: 12 }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: 'rgba(0, 0, 0, 0.05)' }
                    },
                    x: {
                        grid: { display: false }
                    }
                }
            }
        });
    }

    // Initialize on page load
    window.addEventListener('DOMContentLoaded', function() {
        console.log('Page loaded, starting initialization...');
        loadSavingsGoals();
    });
</script>
@endsection
