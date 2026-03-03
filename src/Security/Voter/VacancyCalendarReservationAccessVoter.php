<?php

declare(strict_types=1);

namespace ContaoThemesShop\VacancyCalendar\Security\Voter;

use Contao\CoreBundle\Security\DataContainer\CreateAction;
use Contao\CoreBundle\Security\DataContainer\DeleteAction;
use Contao\CoreBundle\Security\DataContainer\ReadAction;
use Contao\CoreBundle\Security\DataContainer\UpdateAction;
use Contao\CoreBundle\Security\Voter\DataContainer\AbstractDataContainerVoter;
use Contao\CoreBundle\Security\Voter\DataContainer\ParentAccessTrait;
use ContaoThemesShop\VacancyCalendar\Security\VacancyCalendarPermissions;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class VacancyCalendarReservationAccessVoter extends AbstractDataContainerVoter
{
    use ParentAccessTrait;

    protected function getTable(): string
    {
        return 'tl_cts_vacancy_calendar_reservation';
    }

    protected function hasAccess(TokenInterface $token, CreateAction|DeleteAction|ReadAction|UpdateAction $action): bool
    {
        return $this->accessDecisionManager->decide($token, [VacancyCalendarPermissions::USER_CAN_ACCESS_MODULE])
            && $this->hasAccessToParent($token, VacancyCalendarPermissions::USER_CAN_EDIT_VACANCY_CALENDAR, $action);
    }
}
