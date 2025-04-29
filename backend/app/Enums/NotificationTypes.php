<?php

namespace App\Enums;

enum NotificationTypes: string
{
    case NEW_APPLICATION = 'new_application';
    case EVALUATION_SCHEDULED = 'evaluation_scheduled';
    case DATA_FILE_NEEDED = 'data_file_needed';
    case DATA_FILE_FILLED = 'data_file_filled';
    case OTHER = 'other';
    case REJECTED = 'rejected';
    case ACCEPTED = 'accepted';
    case EXPIRED = 'expired';
    case STATUS_CHANGED = 'status_changed';
    case WAITING_LIST = 'waiting_list';
}
