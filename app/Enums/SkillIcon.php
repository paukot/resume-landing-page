<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum SkillIcon: string implements HasLabel
{
    case Database = 'database';
    case Layers = 'layers';
    case Cpu = 'cpu';
    case Code = 'code';
    case Cloud = 'cloud';
    case Globe = 'globe';
    case Sparkles = 'sparkles';
    case Briefcase = 'briefcase';
    case Award = 'award';
    case Star = 'star';
    case GraduationCap = 'graduation-cap';
    case Server = 'server';
    case Terminal = 'terminal';
    case Settings = 'settings';
    case Wrench = 'wrench';
    case Shield = 'shield';
    case Lock = 'lock';
    case Rocket = 'rocket';
    case Zap = 'zap';
    case GitBranch = 'git-branch';
    case Container = 'container';
    case Boxes = 'boxes';
    case Network = 'network';
    case HardDrive = 'hard-drive';
    case Smartphone = 'smartphone';
    case Monitor = 'monitor';
    case Palette = 'palette';
    case Bug = 'bug';
    case Workflow = 'workflow';
    case Package = 'package';
    case FileCode = 'file-code';
    case Braces = 'braces';
    case Bot = 'bot';
    case Webhook = 'webhook';
    case Gauge = 'gauge';
    case Brain = 'brain';
    case CreditCard = 'credit-card';
    case Search = 'search';

    public function getLabel(): string
    {
        return str($this->name)->headline()->toString();
    }
}
