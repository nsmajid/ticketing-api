<?php

namespace App\Shared\Enums\System;

use App\Shared\Enums\Concerns\HasValues;



enum Permission: string
{
    use HasValues;

        // Dashboard
    case DashboardView = 'dashboard.view';

        // User Management
    case UserView = 'user.view';
    case UserCreate = 'user.create';
    case UserUpdate = 'user.update';
    case UserDelete = 'user.delete';
    case RoleManage = 'role.manage';

        // Ticket
    case TicketViewAll = 'ticket.view.all';
    case TicketViewAssigned = 'ticket.view.assigned';
    case TicketViewOwn = 'ticket.view.own';
    case TicketCreate = 'ticket.create';
    case TicketUpdate = 'ticket.update';
    case TicketDelete = 'ticket.delete';
    case TicketReview = 'ticket.review';
    // case TicketReject = 'ticket.reject';
    // case TicketApprove = 'ticket.approve';
    case TicketAssign = 'ticket.assign';
    case TicketReassign = 'ticket.reassign';
    case TicketClose = 'ticket.close';

        // Progress
    case ProgressView = 'progress.view';
    case ProgressCreate = 'progress.create';
    case ProgressUpdate = 'progress.update';

        // Estimation
    case EstimationCreate = 'estimation.create';
    case EstimationUpdate = 'estimation.update';
    case EstimationApprove = 'estimation.approve';

        // Change Request
    case ChangeApprove = 'change.approve';
    case ChangeReject = 'change.reject';

        // Attachment
    case AttachmentView = 'attachment.view';
    case AttachmentUpload = 'attachment.upload';
    case AttachmentDelete = 'attachment.delete';

        // SLA
    case SlaView = 'sla.view';
    case SlaManage = 'sla.manage';

        // Report
    case ReportView = 'report.view';
    case ReportExport = 'report.export';

        // Ticket Category
    case TicketCategoryView = 'ticket-category.view';
    case TicketCategoryCreate = 'ticket-category.create';
    case TicketCategoryUpdate = 'ticket-category.update';
    case TicketCategoryDelete = 'ticket-category.delete';

        // Ticket Priority
    case TicketPriorityView = 'ticket-priority.view';
    case TicketPriorityCreate = 'ticket-priority.create';
    case TicketPriorityUpdate = 'ticket-priority.update';
    case TicketPriorityDelete = 'ticket-priority.delete';

        // Ticket Status
    case TicketStatusView = 'ticket-status.view';
    case TicketStatusCreate = 'ticket-status.create';
    case TicketStatusUpdate = 'ticket-status.update';
    case TicketStatusDelete = 'ticket-status.delete';

        // Application
    case ApplicationView = 'application.view';
    case ApplicationCreate = 'application.create';
    case ApplicationUpdate = 'application.update';
    case ApplicationDelete = 'application.delete';

    case ApplicationFeatureView = 'application-feature.view';
    case ApplicationFeatureCreate = 'application-feature.create';
    case ApplicationFeatureUpdate = 'application-feature.update';
    case ApplicationFeatureDelete = 'application-feature.delete';
}
