<?php

declare(strict_types=1);

namespace Toolkit\Validator\Rules;

use Toolkit\Validator\Contracts\RuleInterface;

/**
 * Validates that the value is a valid IP address (IPv4 or IPv6).
 */
class IpRule implements RuleInterface
{
    public function validate($value, array $params = []): bool
    {
        if ($value === null || $value === '') {
            return true; // Let RequiredRule handle emptiness
        }

        $version = $params[0] ?? 'both';
        
        if ($version === 'ipv4') {
            return filter_var($value, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) !== false;
        }
        
        if ($version === 'ipv6') {
            return filter_var($value, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6) !== false;
        }

        return filter_var($value, FILTER_VALIDATE_IP) !== false;
    }

    public function getMessage(string $field, array $params = []): string
    {
        $version = $params[0] ?? 'valid';
        if ($version === 'ipv4') {
            return "The {$field} field must be a valid IPv4 address.";
        }
        if ($version === 'ipv6') {
            return "The {$field} field must be a valid IPv6 address.";
        }
        return "The {$field} field must be a valid IP address.";
    }
}
