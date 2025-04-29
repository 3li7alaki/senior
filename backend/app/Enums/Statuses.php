<?php

namespace App\Enums;

enum Statuses: string
{
    case NEW = 'new';
    case EVALUATION = 'evaluation';
    case WAITING = 'waiting';
    case ACCEPTED = 'accepted';
    case REJECTED = 'rejected';
    case TRIAL = 'trial';
    case PAYMENT = 'payment';
    case BREAK = 'break';
    case WITHDRAWAL = 'withdrawal';
    case GRADUATED = 'graduated';
    case EARLY_INTERVENTION = 'early_intervention';
    case HABILITATION = 'habilitation';
    case OTHER = 'other';
    case EXPIRED = 'expired';
}
