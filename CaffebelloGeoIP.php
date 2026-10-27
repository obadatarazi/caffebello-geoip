<?php
/**
 * Plugin Name: CAFFÈBELLO Geo Redirect
 * Plugin URI:  https://cupcoding.com
 * Description: Instantly redirects visitors to their language or store based on country. Zero flicker, server-side, lightning fast.
 * Version:     1.2.1
 * Author:      Obada Al-Tarazi
 * Author URI:  https://linkedin.com/in/obada-tarazi
 * License:     GPL2
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * ISO 3166-1 alpha-2 country list (English names) for admin selects + validation.
 *
 * @return array<string,string> code => name
 */
function cgr_get_iso_countries()
{
    static $countries = null;
    if ($countries !== null) {
        return $countries;
    }

    $countries = [
        'AD' => 'Andorra', 'AE' => 'United Arab Emirates', 'AF' => 'Afghanistan', 'AG' => 'Antigua and Barbuda',
        'AI' => 'Anguilla', 'AL' => 'Albania', 'AM' => 'Armenia', 'AO' => 'Angola', 'AQ' => 'Antarctica',
        'AR' => 'Argentina', 'AS' => 'American Samoa', 'AT' => 'Austria', 'AU' => 'Australia', 'AW' => 'Aruba',
        'AX' => 'Aland Islands', 'AZ' => 'Azerbaijan', 'BA' => 'Bosnia and Herzegovina', 'BB' => 'Barbados',
        'BD' => 'Bangladesh', 'BE' => 'Belgium', 'BF' => 'Burkina Faso', 'BG' => 'Bulgaria', 'BH' => 'Bahrain',
        'BI' => 'Burundi', 'BJ' => 'Benin', 'BL' => 'Saint Barthelemy', 'BM' => 'Bermuda', 'BN' => 'Brunei',
        'BO' => 'Bolivia', 'BQ' => 'Bonaire, Sint Eustatius and Saba', 'BR' => 'Brazil', 'BS' => 'Bahamas',
        'BT' => 'Bhutan', 'BV' => 'Bouvet Island', 'BW' => 'Botswana', 'BY' => 'Belarus', 'BZ' => 'Belize',
        'CA' => 'Canada', 'CC' => 'Cocos Islands', 'CD' => 'Congo (DRC)', 'CF' => 'Central African Republic',
        'CG' => 'Congo', 'CH' => 'Switzerland', 'CI' => 'Cote d\'Ivoire', 'CK' => 'Cook Islands', 'CL' => 'Chile',
        'CM' => 'Cameroon', 'CN' => 'China', 'CO' => 'Colombia', 'CR' => 'Costa Rica', 'CU' => 'Cuba',
        'CV' => 'Cabo Verde', 'CW' => 'Curacao', 'CX' => 'Christmas Island', 'CY' => 'Cyprus', 'CZ' => 'Czechia',
        'DE' => 'Germany', 'DJ' => 'Djibouti', 'DK' => 'Denmark', 'DM' => 'Dominica', 'DO' => 'Dominican Republic',
        'DZ' => 'Algeria', 'EC' => 'Ecuador', 'EE' => 'Estonia', 'EG' => 'Egypt', 'EH' => 'Western Sahara',
        'ER' => 'Eritrea', 'ES' => 'Spain', 'ET' => 'Ethiopia', 'FI' => 'Finland', 'FJ' => 'Fiji',
        'FK' => 'Falkland Islands', 'FM' => 'Micronesia', 'FO' => 'Faroe Islands', 'FR' => 'France', 'GA' => 'Gabon',
        'GB' => 'United Kingdom', 'GD' => 'Grenada', 'GE' => 'Georgia', 'GF' => 'French Guiana', 'GG' => 'Guernsey',
        'GH' => 'Ghana', 'GI' => 'Gibraltar', 'GL' => 'Greenland', 'GM' => 'Gambia', 'GN' => 'Guinea',
        'GP' => 'Guadeloupe', 'GQ' => 'Equatorial Guinea', 'GR' => 'Greece', 'GS' => 'South Georgia and the South Sandwich Islands',
        'GT' => 'Guatemala', 'GU' => 'Guam', 'GW' => 'Guinea-Bissau', 'GY' => 'Guyana', 'HK' => 'Hong Kong',
        'HM' => 'Heard Island and McDonald Islands', 'HN' => 'Honduras', 'HR' => 'Croatia', 'HT' => 'Haiti',
        'HU' => 'Hungary', 'ID' => 'Indonesia', 'IE' => 'Ireland', 'IL' => 'Israel', 'IM' => 'Isle of Man',
        'IN' => 'India', 'IO' => 'British Indian Ocean Territory', 'IQ' => 'Iraq', 'IR' => 'Iran', 'IS' => 'Iceland',
        'IT' => 'Italy', 'JE' => 'Jersey', 'JM' => 'Jamaica', 'JO' => 'Jordan', 'JP' => 'Japan', 'KE' => 'Kenya',
        'KG' => 'Kyrgyzstan', 'KH' => 'Cambodia', 'KI' => 'Kiribati', 'KM' => 'Comoros', 'KN' => 'Saint Kitts and Nevis',
        'KP' => 'North Korea', 'KR' => 'South Korea', 'KW' => 'Kuwait', 'KY' => 'Cayman Islands', 'KZ' => 'Kazakhstan',
        'LA' => 'Laos', 'LB' => 'Lebanon', 'LC' => 'Saint Lucia', 'LI' => 'Liechtenstein', 'LK' => 'Sri Lanka',
        'LR' => 'Liberia', 'LS' => 'Lesotho', 'LT' => 'Lithuania', 'LU' => 'Luxembourg', 'LV' => 'Latvia',
        'LY' => 'Libya', 'MA' => 'Morocco', 'MC' => 'Monaco', 'MD' => 'Moldova', 'ME' => 'Montenegro',
        'MF' => 'Saint Martin (French part)', 'MG' => 'Madagascar', 'MH' => 'Marshall Islands', 'MK' => 'North Macedonia',
        'ML' => 'Mali', 'MM' => 'Myanmar', 'MN' => 'Mongolia', 'MO' => 'Macao', 'MP' => 'Northern Mariana Islands',
        'MQ' => 'Martinique', 'MR' => 'Mauritania', 'MS' => 'Montserrat', 'MT' => 'Malta', 'MU' => 'Mauritius',
        'MV' => 'Maldives', 'MW' => 'Malawi', 'MX' => 'Mexico', 'MY' => 'Malaysia', 'MZ' => 'Mozambique',
        'NA' => 'Namibia', 'NC' => 'New Caledonia', 'NE' => 'Niger', 'NF' => 'Norfolk Island', 'NG' => 'Nigeria',
        'NI' => 'Nicaragua', 'NL' => 'Netherlands', 'NO' => 'Norway', 'NP' => 'Nepal', 'NR' => 'Nauru',
        'NU' => 'Niue', 'NZ' => 'New Zealand', 'OM' => 'Oman', 'PA' => 'Panama', 'PE' => 'Peru', 'PF' => 'French Polynesia',
        'PG' => 'Papua New Guinea', 'PH' => 'Philippines', 'PK' => 'Pakistan', 'PL' => 'Poland', 'PM' => 'Saint Pierre and Miquelon',
        'PN' => 'Pitcairn', 'PR' => 'Puerto Rico', 'PS' => 'Palestine', 'PT' => 'Portugal', 'PW' => 'Palau',
        'PY' => 'Paraguay', 'QA' => 'Qatar', 'RE' => 'Reunion', 'RO' => 'Romania', 'RS' => 'Serbia', 'RU' => 'Russia',
        'RW' => 'Rwanda', 'SA' => 'Saudi Arabia', 'SB' => 'Solomon Islands', 'SC' => 'Seychelles', 'SD' => 'Sudan',
        'SE' => 'Sweden', 'SG' => 'Singapore', 'SH' => 'Saint Helena', 'SI' => 'Slovenia', 'SJ' => 'Svalbard and Jan Mayen',
        'SK' => 'Slovakia', 'SL' => 'Sierra Leone', 'SM' => 'San Marino', 'SN' => 'Senegal', 'SO' => 'Somalia',
        'SR' => 'Suriname', 'SS' => 'South Sudan', 'ST' => 'Sao Tome and Principe', 'SV' => 'El Salvador',
        'SX' => 'Sint Maarten (Dutch part)', 'SY' => 'Syria', 'SZ' => 'Eswatini', 'TC' => 'Turks and Caicos Islands',
        'TD' => 'Chad', 'TF' => 'French Southern Territories', 'TG' => 'Togo', 'TH' => 'Thailand', 'TJ' => 'Tajikistan',
        'TK' => 'Tokelau', 'TL' => 'Timor-Leste', 'TM' => 'Turkmenistan', 'TN' => 'Tunisia', 'TO' => 'Tonga',
        'TR' => 'Turkey', 'TT' => 'Trinidad and Tobago', 'TV' => 'Tuvalu', 'TW' => 'Taiwan', 'TZ' => 'Tanzania',
        'UA' => 'Ukraine', 'UG' => 'Uganda', 'UM' => 'United States Minor Outlying Islands', 'US' => 'United States',
        'UY' => 'Uruguay', 'UZ' => 'Uzbekistan', 'VA' => 'Holy See', 'VC' => 'Saint Vincent and the Grenadines',
        'VE' => 'Venezuela', 'VG' => 'British Virgin Islands', 'VI' => 'U.S. Virgin Islands', 'VN' => 'Vietnam',
        'VU' => 'Vanuatu', 'WF' => 'Wallis and Futuna', 'WS' => 'Samoa', 'YE' => 'Yemen', 'YT' => 'Mayotte',
        'ZA' => 'South Africa', 'ZM' => 'Zambia', 'ZW' => 'Zimbabwe',
    ];

    return $countries;
}

/**
 * @return array<int,array{country:string,target_type:string,target:string}>
 */
function cgr_get_default_rules()
{
    return [
        [
            'country'     => 'NL',
            'target_type' => 'path',
            'target'      => '/nl',
        ],
    ];
}

/**
 * Normalize + validate redirect rules.
 *
 * @param mixed $raw
 * @return array<int,array{country:string,target_type:string,target:string}>
 */
function cgr_sanitize_redirect_rules($raw)
{
    $valid_countries = cgr_get_iso_countries();
    $out             = [];

    if (!is_array($raw)) {
        return $out;
    }

    foreach ($raw as $row) {
        if (!is_array($row)) {
            continue;
        }

        $country = isset($row['country']) ? strtoupper(sanitize_text_field((string) $row['country'])) : '';
        if ($country === '' || !isset($valid_countries[$country])) {
            continue;
        }

        $type = isset($row['target_type']) ? sanitize_key((string) $row['target_type']) : 'path';
        if ($type !== 'path' && $type !== 'domain') {
            $type = 'path';
        }

        $target = isset($row['target']) ? trim((string) wp_unslash($row['target'])) : '';
        if ($target === '') {
            continue;
        }

        if ($type === 'path') {
            $target = '/' . ltrim($target, '/');
        } else {
            if (!preg_match('#^[a-z][a-z0-9+.-]*://#i', $target)) {
                $target = 'https://' . ltrim($target, '/');
            }
        }

        $out[] = [
            'country'     => $country,
            'target_type' => $type,
            'target'      => $target,
        ];
    }

    return $out;
}

/**
 * Settings API sanitize: avoid wiping rules when other settings are saved.
 *
 * @param mixed $value
 * @return array<int,array{country:string,target_type:string,target:string}>
 */
function cgr_sanitize_redirect_rules_preserve($value)
{
    // If rules weren't posted as part of this save, keep the previous value.
    // (Prevents clearing rules when saving other settings in the same group.)
    if (!isset($_POST['cgr_redirect_rules'])) {
        return cgr_sanitize_redirect_rules(get_option('cgr_redirect_rules', []));
    }

    return cgr_sanitize_redirect_rules($value);
}

function cgr_sanitize_redirect_scope($raw)
{
    $v = sanitize_key((string) $raw);
    return ($v === 'all_paths') ? 'all_paths' : 'homepage';
}

/**
 * Migrate legacy single-rule options into the new rules array (once).
 *
 * @return array<int,array{country:string,target_type:string,target:string}>
 */
function cgr_migrate_legacy_rules_if_needed()
{
    $rules = get_option('cgr_redirect_rules', null);
    if (is_array($rules) && !empty($rules)) {
        return cgr_sanitize_redirect_rules($rules);
    }

    $legacy_country = strtoupper((string) get_option('cgr_target_country', 'NL'));
    $legacy_path    = trim((string) get_option('cgr_target_path', '/nl'));
    if ($legacy_path === '') {
        $legacy_path = '/nl';
    }
    $legacy_path = '/' . ltrim($legacy_path, '/');

    $migrated = cgr_sanitize_redirect_rules([
        [
            'country'     => $legacy_country,
            'target_type' => 'path',
            'target'      => $legacy_path,
        ],
    ]);

    if (!empty($migrated)) {
        update_option('cgr_redirect_rules', $migrated, false);
    }

    return $migrated;
}

/**
 * @return array<int,array{country:string,target_type:string,target:string}>
 */
function cgr_get_redirect_rules()
{
    $rules = cgr_migrate_legacy_rules_if_needed();
    if (empty($rules)) {
        $rules = cgr_get_default_rules();
    }
    return $rules;
}

function cgr_get_redirect_scope()
{
    return cgr_sanitize_redirect_scope(get_option('cgr_redirect_scope', 'homepage'));
}

/**
 * Best-effort client IP (same logic as settings page + redirect).
 */
function cgr_get_client_ip()
{
    $ip = isset($_SERVER['REMOTE_ADDR']) ? (string) $_SERVER['REMOTE_ADDR'] : '';
    if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $xff = (string) $_SERVER['HTTP_X_FORWARDED_FOR'];
        $ip  = trim(explode(',', $xff)[0]);
    }
    return $ip;
}

/**
 * @param array{country:string,target_type:string,target:string} $rule
 */
function cgr_build_destination_for_request($rule, $request_uri)
{
    $request_uri = is_string($request_uri) ? $request_uri : '/';
    $req_path    = wp_parse_url($request_uri, PHP_URL_PATH) ?: '/';
    $req_query   = wp_parse_url($request_uri, PHP_URL_QUERY);

    $suffix = '';
    if (is_string($req_query) && $req_query !== '') {
        $suffix = '?' . $req_query;
    }

    if ($rule['target_type'] === 'path') {
        $base_path = '/' . ltrim((string) $rule['target'], '/');
        if ($req_path === '/' || $req_path === '') {
            $url = home_url($base_path . '/');
        } else {
            $rel = ltrim($req_path, '/');
            $url = home_url(trailingslashit($base_path) . $rel);
        }

        if (is_string($req_query) && $req_query !== '') {
            $url .= (strpos($url, '?') === false ? '?' : '&') . $req_query;
        }
        return $url;
    }

    $target = $rule['target'];
    $parts  = wp_parse_url($target);
    if (!is_array($parts) || empty($parts['host'])) {
        return '';
    }

    $scheme = isset($parts['scheme']) ? $parts['scheme'] : 'https';
    $host   = $parts['host'];
    $port   = isset($parts['port']) ? (int) $parts['port'] : null;

    $site_host = wp_parse_url(home_url('/'), PHP_URL_HOST);
    $is_same   = $site_host && strcasecmp((string) $site_host, (string) $host) === 0;

    if ($is_same) {
        $path_on_target = isset($parts['path']) ? (string) $parts['path'] : '/';
        if ($path_on_target === '' || $path_on_target === '/') {
            $merged = ($req_path === '/' || $req_path === '') ? '/' : $req_path;
        } else {
            $merged = ($req_path === '/' || $req_path === '')
                ? $path_on_target
                : trailingslashit(rtrim($path_on_target, '/')) . ltrim($req_path, '/');
        }
        $merged = '/' . ltrim($merged, '/');
        $url    = home_url($merged);
        if (is_string($req_query) && $req_query !== '') {
            $url .= (strpos($url, '?') === false ? '?' : '&') . $req_query;
        }
        return $url;
    }

    $path_on_target = isset($parts['path']) ? (string) $parts['path'] : '/';
    if ($req_path === '/' || $req_path === '') {
        $final_path = $path_on_target;
    } else {
        if ($path_on_target === '' || $path_on_target === '/') {
            $final_path = $req_path;
        } else {
            $final_path = trailingslashit(rtrim($path_on_target, '/')) . ltrim($req_path, '/');
        }
    }

    $user = isset($parts['user']) ? $parts['user'] : '';
    $pass = isset($parts['pass']) ? $parts['pass'] : '';
    $auth = '';
    if ($user !== '') {
        $auth = rawurlencode($user);
        if ($pass !== '') {
            $auth .= ':' . rawurlencode($pass);
        }
        $auth .= '@';
    }

    $port_str = ($port && !in_array($port, [80, 443], true)) ? ':' . $port : '';
    $built    = $scheme . '://' . $auth . $host . $port_str . $final_path;
    if (is_string($req_query) && $req_query !== '') {
        $built .= '?' . $req_query;
    }
    return $built;
}

/**
 * Prevent redirect loops for a computed destination URL.
 */
function cgr_would_loop($destination, $current_url)
{
    $a = wp_parse_url($destination);
    $b = wp_parse_url($current_url);
    if (!is_array($a) || !is_array($b)) {
        return false;
    }

    $scheme_a = isset($a['scheme']) ? strtolower((string) $a['scheme']) : '';
    $scheme_b = isset($b['scheme']) ? strtolower((string) $b['scheme']) : '';
    $host_a   = isset($a['host']) ? strtolower((string) $a['host']) : '';
    $host_b   = isset($b['host']) ? strtolower((string) $b['host']) : '';

    $path_a = isset($a['path']) ? rtrim((string) $a['path'], '/') : '';
    $path_b = isset($b['path']) ? rtrim((string) $b['path'], '/') : '';

    if ($path_a === '') {
        $path_a = '/';
    }
    if ($path_b === '') {
        $path_b = '/';
    }

    $query_a = isset($a['query']) ? (string) $a['query'] : '';
    $query_b = isset($b['query']) ? (string) $b['query'] : '';

    return $scheme_a === $scheme_b && $host_a === $host_b && $path_a === $path_b && $query_a === $query_b;
}

/**
 * GeoIP lookup for an IP (returns WP_Error on failure).
 *
 * @return array{name:string,code:string}|WP_Error
 */
function cgr_geoip_lookup_country($ip)
{
    $autoload = WP_CONTENT_DIR . '/uploads/geoip2/geoip2.phar';
    $db_path  = get_option('cgr_db_path', WP_CONTENT_DIR . '/uploads/GeoLite2-Country.mmdb');

    if (!file_exists($autoload)) {
        return new WP_Error('cgr_no_lib', 'GeoIP2 PHP library not found.');
    }
    if (!file_exists($db_path)) {
        return new WP_Error('cgr_no_db', 'Database file not found.');
    }
    if ($ip === '') {
        return new WP_Error('cgr_no_ip', 'Missing IP address.');
    }

    require_once $autoload;

    try {
        $reader  = new \GeoIp2\Database\Reader($db_path);
        $record  = $reader->country($ip);
        $country = strtoupper((string) ($record->country->isoCode ?? ''));
        $name    = (string) ($record->country->name ?? '');

        if ($country === '') {
            return new WP_Error('cgr_unknown', 'Could not resolve a country for that IP.');
        }

        return [
            'name' => $name,
            'code' => $country,
        ];
    } catch (\Exception $e) {
        return new WP_Error('cgr_geoip', $e->getMessage());
    }
}

/**
 * Render one redirect rule row for the settings UI.
 *
 * @param array{country:string,target_type:string,target:string} $rule
 */
function cgr_render_rule_row($index, $rule, $is_template = false)
{
    $countries = cgr_get_iso_countries();
    asort($countries, SORT_NATURAL | SORT_FLAG_CASE);

    $idx = (int) $index;
    $sel = strtoupper((string) ($rule['country'] ?? ''));
    $type = isset($rule['target_type']) ? (string) $rule['target_type'] : 'path';
    if ($type !== 'path' && $type !== 'domain') {
        $type = 'path';
    }
    $target = isset($rule['target']) ? (string) $rule['target'] : '';

    $name_prefix = $is_template ? 'cgr_redirect_rules_template' : 'cgr_redirect_rules[' . $idx . ']';

    $search_val = '';
    if ($sel !== '' && isset($countries[$sel])) {
        $search_val = $countries[$sel] . ' (' . $sel . ')';
    }
    ?>
    <div class="cgr-rule"<?php echo $is_template ? ' style="display:none" data-cgr-rule-template="1"' : ''; ?>>
        <div class="cgr-rule-grid">
            <div class="cgr-country-field">
                <label class="cgr-label-with-icon">
                    <span class="dashicons dashicons-flag" aria-hidden="true"></span>
                    <?php esc_html_e('Country', 'caffebello-geo-redirect'); ?>
                </label>
                <input type="text"
                       class="cgr-country-search"
                       autocomplete="off"
                       placeholder="Search by country name..."
                       value="<?php echo esc_attr($search_val); ?>" />
                <select class="cgr-country-select" name="<?php echo esc_attr($name_prefix); ?>[country]" <?php echo $is_template ? '' : 'required'; ?>>
                    <option value=""><?php echo esc_html($is_template ? 'Select a country' : 'Select a country'); ?></option>
                    <?php foreach ($countries as $code => $name): ?>
                        <option value="<?php echo esc_attr($code); ?>" <?php selected($sel, $code); ?>>
                            <?php echo esc_html($name . ' (' . $code . ')'); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="cgr-form-row" style="margin:0">
                <label class="cgr-label-with-icon">
                    <span class="dashicons dashicons-admin-links" aria-hidden="true"></span>
                    <?php esc_html_e('Target type', 'caffebello-geo-redirect'); ?>
                </label>
                <select name="<?php echo esc_attr($name_prefix); ?>[target_type]" class="cgr-target-type">
                    <option value="path" <?php selected($type, 'path'); ?>>Path (same site)</option>
                    <option value="domain" <?php selected($type, 'domain'); ?>>Domain / URL</option>
                </select>
            </div>

            <div class="cgr-form-row" style="margin:0">
                <label class="cgr-label-with-icon">
                    <span class="dashicons dashicons-admin-site-alt3" aria-hidden="true"></span>
                    <?php esc_html_e('Target', 'caffebello-geo-redirect'); ?>
                </label>
                <input type="text"
                       name="<?php echo esc_attr($name_prefix); ?>[target]"
                       value="<?php echo esc_attr($target); ?>"
                       placeholder="/nl or https://example.com/..." />
            </div>

            <div class="cgr-rule-actions" role="group" aria-label="<?php esc_attr_e('Rule actions', 'caffebello-geo-redirect'); ?>">
                <button type="button" class="cgr-icon-btn cgr-add-rule" title="<?php esc_attr_e('Add rule', 'caffebello-geo-redirect'); ?>">
                    <span class="dashicons dashicons-plus" aria-hidden="true"></span>
                    <span class="screen-reader-text"><?php esc_html_e('Add rule', 'caffebello-geo-redirect'); ?></span>
                </button>
                <button type="button" class="cgr-icon-btn danger cgr-remove-rule" title="<?php esc_attr_e('Remove rule', 'caffebello-geo-redirect'); ?>">
                    <span class="dashicons dashicons-trash" aria-hidden="true"></span>
                    <span class="screen-reader-text"><?php esc_html_e('Remove rule', 'caffebello-geo-redirect'); ?></span>
                </button>
            </div>
        </div>
        <div class="cgr-muted">
            <span class="cgr-muted-line">
                <span class="dashicons dashicons-admin-links" aria-hidden="true"></span>
                <strong><?php esc_html_e('Path', 'caffebello-geo-redirect'); ?></strong>
                <?php esc_html_e('uses your WordPress home URL as the base (example:', 'caffebello-geo-redirect'); ?>
                <code>/nl</code>).
            </span>
            <span class="cgr-muted-line">
                <span class="dashicons dashicons-admin-site-alt3" aria-hidden="true"></span>
                <strong><?php esc_html_e('Domain / URL', 'caffebello-geo-redirect'); ?></strong>
                <?php esc_html_e('can be another domain or full URL (example:', 'caffebello-geo-redirect'); ?>
                <code>https://nl.example.com</code>).
            </span>
        </div>
    </div>
    <?php
}

add_action('admin_enqueue_scripts', function ($hook) {
    if ($hook !== 'settings_page_caffebello-geo-redirect') {
        return;
    }

    wp_enqueue_style('dashicons');

    wp_register_script('cgr-admin', false, [], false, true);
    wp_enqueue_script('cgr-admin');

    wp_localize_script('cgr-admin', 'cgrAdmin', [
        'ajaxUrl' => admin_url('admin-ajax.php'),
        'nonce'   => wp_create_nonce('cgr_test_geoip'),
    ]);

    $inline = <<<'JS'
(function() {
    function cgrFilterSelect(selectEl, q) {
        q = (q || '').toLowerCase().trim();
        var opts = selectEl.querySelectorAll('option');
        var firstVisible = null;
        for (var i = 0; i < opts.length; i++) {
            var o = opts[i];
            if (!o.value) { o.hidden = false; continue; }
            var txt = (o.textContent || '').toLowerCase();
            var hidden = q.length > 0 && txt.indexOf(q) === -1;
            o.hidden = hidden;
            if (!hidden && !firstVisible) firstVisible = o;
        }
        var cur = selectEl.options[selectEl.selectedIndex];
        if (cur && cur.hidden && firstVisible) {
            selectEl.value = firstVisible.value;
        }
    }

    function cgrBindRuleRow(row) {
        var search = row.querySelector('.cgr-country-search');
        var select = row.querySelector('.cgr-country-select');
        if (search && select) {
            search.addEventListener('input', function() {
                cgrFilterSelect(select, search.value);
            });
        }

        var addBtn = row.querySelector('.cgr-add-rule');
        if (addBtn) {
            addBtn.addEventListener('click', function() {
                var tpl = document.querySelector('[data-cgr-rule-template="1"]');
                if (!tpl) return;
                var wrap = document.getElementById('cgr-rules');
                if (!wrap) return;

                var clone = tpl.cloneNode(true);
                clone.style.display = '';
                clone.removeAttribute('data-cgr-rule-template');

                var idx = wrap.querySelectorAll('.cgr-rule:not([data-cgr-rule-template="1"])').length;
                clone.querySelectorAll('select, input').forEach(function(el) {
                    if (!el.name) return;
                    el.name = el.name.replace('cgr_redirect_rules_template', 'cgr_redirect_rules[' + idx + ']');
                });

                clone.querySelectorAll('input, select').forEach(function(el) {
                    if (el.classList && el.classList.contains('cgr-country-search')) el.value = '';
                    if (el.tagName === 'SELECT' && el.classList.contains('cgr-country-select')) el.selectedIndex = 0;
                    if (el.name && el.name.indexOf('[target]') !== -1) el.value = '';
                });

                wrap.appendChild(clone);
                cgrBindRuleRow(clone);
            });
        }

        var rmBtn = row.querySelector('.cgr-remove-rule');
        if (rmBtn) {
            rmBtn.addEventListener('click', function() {
                var wrap = document.getElementById('cgr-rules');
                if (!wrap) return;
                var rows = wrap.querySelectorAll('.cgr-rule:not([data-cgr-rule-template="1"])');
                if (rows.length <= 1) return;
                row.parentNode.removeChild(row);
            });
        }
    }

    function cgrInitRules() {
        var wrap = document.getElementById('cgr-rules');
        if (!wrap) return;
        wrap.querySelectorAll('.cgr-rule').forEach(function(row) {
            if (row.getAttribute('data-cgr-rule-template') === '1') return;
            cgrBindRuleRow(row);
        });
        var tpl = wrap.querySelector('[data-cgr-rule-template="1"]');
        if (tpl) cgrBindRuleRow(tpl);
    }

    function cgrInitTest() {
        var btn = document.getElementById('cgr-test-ip-btn');
        var out = document.getElementById('cgr-test-ip-result');
        var input = document.getElementById('cgr-test-ip');
        if (!btn || !out) return;

        btn.addEventListener('click', function() {
            out.innerHTML = '';
            out.className = '';

            var fd = new FormData();
            fd.append('action', 'cgr_test_geoip');
            fd.append('nonce', cgrAdmin.nonce);
            if (input) fd.append('ip', input.value || '');

            fetch(cgrAdmin.ajaxUrl, { method: 'POST', credentials: 'same-origin', body: fd })
                .then(function(r) { return r.json(); })
                .then(function(r) {
                    if (!r || !r.success) {
                        var msg = (r && r.data && r.data.message) ? r.data.message : 'Request failed.';
                        out.className = 'cgr-test-err';
                        out.innerHTML = '<strong>Error:</strong> ' + msg;
                        return;
                    }
                    var d = r.data || {};
                    out.className = 'cgr-test-ok';
                    out.innerHTML = '<span class="dashicons dashicons-yes-alt" style="color:#00a32a;vertical-align:middle;margin-right:6px"></span>' +
                        'Detected: <strong>' + (d.name || '') + '</strong> (' + (d.code || '') + ')';
                })
                .catch(function() {
                    out.className = 'cgr-test-err';
                    out.innerHTML = '<strong>Error:</strong> Network error.';
                });
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        cgrInitRules();
        cgrInitTest();
    });
})();
JS;

    wp_add_inline_script('cgr-admin', $inline);
});

// ============================================================
// 1. ADMIN STYLES
// ============================================================
add_action('admin_head', function () {
    $screen = get_current_screen();
    if (!$screen || strpos($screen->id, 'caffebello-geo-redirect') === false) {
        return;
    }
    ?>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

        .cgr-page * { box-sizing: border-box; font-family: Inter, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif; }
        /* Dashicons must keep their own font, otherwise all icons look wrong. */
        .cgr-page .dashicons,
        .cgr-page .dashicons:before {
            font-family: dashicons !important;
            font-weight: 400;
            font-style: normal;
        }

        .cgr-page { max-width: 1100px; padding: 20px 20px 40px 0; }

        .cgr-page-title {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 24px;
        }
        .cgr-page-title h1 {
            font-size: 23px !important;
            font-weight: 600 !important;
            color: #1d2327 !important;
            margin: 0 !important;
            padding: 0 !important;
            line-height: 1.3 !important;
            display: block !important;
        }
        .cgr-version {
            display: inline-block;
            padding: 2px 9px;
            background: #f0f6fc;
            border: 1px solid #c5d9ed;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 500;
            color: #0073aa;
            margin-left: 6px;
            vertical-align: middle;
        }

        .cgr-layout {
            display: grid;
            grid-template-columns: 1fr 320px;
            gap: 20px;
            align-items: start;
        }

        .cgr-box {
            background: #fff;
            border: 1px solid #c3c4c7;
            border-radius: 4px;
            box-shadow: 0 1px 1px rgba(0,0,0,.04);
            margin-bottom: 20px;
        }
        .cgr-box-header {
            display: flex;
            align-items: center;
            padding: 12px 16px;
            border-bottom: 1px solid #f0f0f1;
        }
        .cgr-box-title {
            font-size: 13px !important;
            font-weight: 600 !important;
            color: #1d2327 !important;
            margin: 0 !important;
            padding: 0 !important;
            display: flex !important;
            align-items: center;
            gap: 7px;
        }
        .cgr-box-title .dashicons {
            color: #646970;
            font-size: 16px;
            width: 16px;
            height: 16px;
        }
        .cgr-box-body { padding: 16px; }

        .cgr-form-row { margin-bottom: 16px; }
        .cgr-form-row:last-child { margin-bottom: 0; }
        .cgr-form-row label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: #1d2327;
            margin-bottom: 5px;
        }
        .cgr-form-row input[type=text] {
            width: 100%;
            padding: 6px 10px;
            font-size: 14px;
            border: 1px solid #8c8f94;
            border-radius: 4px;
            color: #1d2327;
            background: #fff;
            outline: none;
            transition: border-color .15s, box-shadow .15s;
        }
        .cgr-form-row input[type=text]:focus {
            border-color: #2271b1;
            box-shadow: 0 0 0 1px #2271b1;
        }
        .cgr-form-row input.cgr-short { width: 80px; }
        .cgr-desc {
            margin-top: 5px;
            font-size: 12px;
            color: #646970;
            line-height: 1.5;
        }
        .cgr-desc a { color: #2271b1; text-decoration: none; }
        .cgr-desc a:hover { text-decoration: underline; }
        .cgr-desc code {
            background: #f6f7f7;
            border: 1px solid #dcdcde;
            border-radius: 3px;
            padding: 1px 5px;
            font-size: 11px;
            color: #1d2327;
        }

        .cgr-submit-row {
            padding: 12px 16px;
            border-top: 1px solid #f0f0f1;
            background: #f6f7f7;
            border-radius: 0 0 4px 4px;
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 10px;
        }

        .cgr-btn-primary {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 14px;
            background: #2271b1;
            border: 1px solid #2271b1;
            border-radius: 3px;
            color: #fff !important;
            font-size: 13px;
            font-weight: 500;
            cursor: pointer;
            text-decoration: none !important;
            font-family: inherit;
            line-height: 1.6;
            transition: background .1s, border-color .1s;
        }
        .cgr-btn-primary:hover {
            background: #135e96;
            border-color: #135e96;
            color: #fff !important;
        }
        .cgr-btn-secondary {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 14px;
            background: #fff;
            border: 1px solid #2271b1;
            border-radius: 3px;
            color: #2271b1 !important;
            font-size: 13px;
            font-weight: 500;
            cursor: pointer;
            text-decoration: none !important;
            font-family: inherit;
            line-height: 1.6;
            transition: background .1s;
        }
        .cgr-btn-secondary:hover {
            background: #f0f6fc;
            color: #135e96 !important;
        }

        .cgr-status-list { display: flex; flex-direction: column; gap: 6px; }
        .cgr-status-row {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 9px 10px;
            border-radius: 3px;
            background: #f6f7f7;
            border: 1px solid #f0f0f1;
        }
        .cgr-indicator {
            width: 10px; height: 10px;
            border-radius: 50%;
            flex-shrink: 0;
        }
        .cgr-indicator.ok   { background: #00a32a; }
        .cgr-indicator.err  { background: #d63638; }
        .cgr-indicator.warn { background: #dba617; }
        .cgr-status-name {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 13px;
            color: #1d2327;
            flex: 1;
            font-weight: 500;
        }
        .cgr-status-name .dashicons {
            font-size: 16px;
            width: 16px;
            height: 16px;
            color: #646970;
            flex-shrink: 0;
        }
        .cgr-status-tag {
            font-size: 11px;
            font-weight: 600;
            padding: 2px 8px;
            border-radius: 3px;
        }
        .cgr-status-tag.ok   { background: #edfaef; color: #00a32a; }
        .cgr-status-tag.err  { background: #fce9e9; color: #d63638; }
        .cgr-status-tag.warn { background: #fef8ee; color: #dba617; }

        .cgr-notice-ok {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 14px;
            background: #edfaef;
            border-left: 4px solid #00a32a;
            border-radius: 0 3px 3px 0;
            margin-bottom: 20px;
            font-size: 13px;
            color: #1d2327;
        }

        .cgr-ip-row {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 8px 12px;
            background: #f6f7f7;
            border: 1px solid #dcdcde;
            border-radius: 4px;
            margin-bottom: 12px;
        }
        .cgr-ip-label { font-size: 12px; color: #646970; }
        .cgr-ip-val   { font-size: 13px; font-weight: 600; color: #1d2327; font-family: monospace; }
        .cgr-live-dot {
            width: 7px; height: 7px;
            background: #00a32a;
            border-radius: 50%;
            animation: cgr-blink 2s infinite;
        }
        @keyframes cgr-blink { 0%,100%{opacity:1}50%{opacity:.3} }

        .cgr-test-ok {
            margin-top: 12px;
            padding: 10px 12px;
            background: #edfaef;
            border-left: 4px solid #00a32a;
            border-radius: 0 3px 3px 0;
            font-size: 13px;
            color: #1d2327;
        }
        .cgr-test-err {
            margin-top: 12px;
            padding: 10px 12px;
            background: #fce9e9;
            border-left: 4px solid #d63638;
            border-radius: 0 3px 3px 0;
            font-size: 13px;
            color: #1d2327;
        }

        .cgr-flow {
            display: flex;
            align-items: center;
            gap: 4px;
            flex-wrap: wrap;
        }
        .cgr-flow-step {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 5px;
        }
        .cgr-flow-icon {
            width: 38px; height: 38px;
            background: #f6f7f7;
            border: 1px solid #dcdcde;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 17px;
        }
        .cgr-flow-label {
            font-size: 10px;
            color: #646970;
            text-align: center;
            max-width: 52px;
            line-height: 1.3;
            font-weight: 500;
        }
        .cgr-flow-arrow { color: #c3c4c7; font-size: 16px; margin: 0 2px; margin-bottom: 16px; }

        .cgr-perf-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            margin-top: 14px;
            padding: 4px 10px;
            background: #f0f6fc;
            border: 1px solid #c5d9ed;
            border-radius: 3px;
            font-size: 11px;
            color: #2271b1;
            font-weight: 600;
        }

        .cgr-steps { counter-reset: cgr-step; display: flex; flex-direction: column; gap: 8px; }
        .cgr-step {
            display: flex;
            gap: 10px;
            align-items: flex-start;
            padding: 10px 12px;
            background: #f6f7f7;
            border: 1px solid #f0f0f1;
            border-radius: 4px;
        }
        .cgr-step-num {
            width: 22px; height: 22px;
            background: #2271b1;
            color: #fff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 11px;
            font-weight: 700;
            flex-shrink: 0;
            margin-top: 1px;
        }
        .cgr-step-text { font-size: 12px; color: #1d2327; line-height: 1.6; }
        .cgr-step-text a { color: #2271b1; }
        .cgr-step-text strong { font-weight: 600; }
        .cgr-step-text code {
            background: #f6f7f7;
            border: 1px solid #dcdcde;
            border-radius: 3px;
            padding: 1px 4px;
            font-size: 11px;
        }

        .cgr-divider { height: 1px; background: #f0f0f1; margin: 14px 0; }

        .cgr-rules { display: flex; flex-direction: column; gap: 12px; }
        .cgr-rule {
            border: 1px solid #dcdcde;
            border-radius: 4px;
            background: #fbfbfc;
            padding: 12px;
        }
        .cgr-rule-grid {
            display: grid;
            grid-template-columns: 1fr 160px 1fr auto;
            gap: 10px;
            align-items: end;
        }
        @media (max-width: 900px) {
            .cgr-rule-grid { grid-template-columns: 1fr; align-items: stretch; }
        }
        .cgr-country-field label { margin-bottom: 6px; }
        .cgr-label-with-icon {
            display: flex;
            align-items: center;
            gap: 6px;
            margin-bottom: 6px;
            font-size: 13px;
            font-weight: 600;
            color: #1d2327;
        }
        .cgr-label-with-icon .dashicons {
            color: #646970;
            font-size: 16px;
            width: 16px;
            height: 16px;
            flex-shrink: 0;
        }
        .cgr-country-search {
            width: 100%;
            padding: 6px 10px;
            font-size: 13px;
            border: 1px solid #8c8f94;
            border-radius: 4px;
            margin-bottom: 6px;
            background: #fff;
        }
        .cgr-country-select {
            width: 100%;
            max-width: 100%;
            padding: 6px 8px;
            font-size: 13px;
            border: 1px solid #8c8f94;
            border-radius: 4px;
            background: #fff;
        }
        .cgr-rule-actions { display: flex; gap: 8px; justify-content: flex-end; align-items: center; }
        .cgr-icon-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 34px;
            height: 34px;
            border-radius: 3px;
            border: 1px solid #c3c4c7;
            background: #fff;
            cursor: pointer;
            color: #1d2327;
        }
        .cgr-icon-btn .dashicons {
            font-size: 18px;
            width: 18px;
            height: 18px;
            line-height: 1;
        }
        .cgr-icon-btn:hover { border-color: #2271b1; color: #2271b1; }
        .cgr-icon-btn.danger:hover { border-color: #d63638; color: #d63638; }
        .cgr-muted {
            font-size: 12px;
            color: #646970;
            margin-top: 10px;
            line-height: 1.6;
            display: flex;
            flex-direction: column;
            gap: 6px;
        }
        .cgr-muted-line { display: flex; align-items: flex-start; gap: 6px; flex-wrap: wrap; }
        .cgr-muted-line .dashicons {
            color: #8c8f94;
            font-size: 14px;
            width: 14px;
            height: 14px;
            margin-top: 2px;
            flex-shrink: 0;
        }

        .cgr-test-actions { display: flex; gap: 10px; align-items: center; flex-wrap: wrap; margin-top: 10px; }
        .cgr-test-actions input[type=text] {
            width: 260px;
            max-width: 100%;
            padding: 6px 10px;
            font-size: 13px;
            border: 1px solid #8c8f94;
            border-radius: 4px;
            font-family: monospace;
        }

        .cgr-desc-inline {
            display: inline-flex;
            align-items: center;
            gap: 3px;
            margin: 0 2px;
            vertical-align: baseline;
        }
        .cgr-desc-inline .dashicons {
            font-size: 14px;
            width: 14px;
            height: 14px;
            color: #646970;
        }

        .cgr-page > h1 { display: none !important; }
    </style>
    <?php
});

// ============================================================
// 2. ADMIN MENU
// ============================================================
add_action('admin_menu', function () {
    add_options_page(
        'Geo Redirect',
        'Geo Redirect',
        'manage_options',
        'caffebello-geo-redirect',
        'caffebello_settings_page'
    );
});

add_action('admin_init', function () {
    register_setting('caffebello_geo_redirect', 'cgr_db_path');
    register_setting('caffebello_geo_redirect', 'cgr_redirect_rules', [
        'sanitize_callback' => 'cgr_sanitize_redirect_rules_preserve',
    ]);
    register_setting('caffebello_geo_redirect', 'cgr_redirect_scope', [
        'sanitize_callback' => 'cgr_sanitize_redirect_scope',
        'default'             => 'homepage',
    ]);

    // Legacy single-rule settings (kept for backwards compatibility / migration).
    register_setting('caffebello_geo_redirect', 'cgr_target_country', ['default' => 'NL']);
    register_setting('caffebello_geo_redirect', 'cgr_target_path', ['default' => '/nl']);
});

// ============================================================
// 3. SETTINGS PAGE
// ============================================================
function caffebello_settings_page()
{
    $db_path = get_option('cgr_db_path', WP_CONTENT_DIR . '/uploads/GeoLite2-Country.mmdb');

    $rules        = cgr_get_redirect_rules();
    $redirect_scope = cgr_get_redirect_scope();

    $db_ok    = file_exists($db_path);
    $autoload = WP_CONTENT_DIR . '/uploads/geoip2/geoip2.phar';
    $lib_ok   = file_exists($autoload);
    $active   = $db_ok && $lib_ok;

    $ip = cgr_get_client_ip();

    $saved = isset($_GET['settings-updated']) && $_GET['settings-updated'];
    ?>
    <div class="wrap cgr-page">
        <h1>Geo Redirect</h1>

        <div class="cgr-page-title">
            <span class="dashicons dashicons-location" style="font-size:30px;width:30px;height:30px;color:#2271b1;margin-top:1px"></span>
            <div>
                <h1>Geo Redirect <span class="cgr-version">v1.1.0</span></h1>
                <p style="margin:2px 0 0;font-size:13px;color:#646970">Server-side country detection — instant redirect, zero flicker</p>
            </div>
        </div>

        <?php if ($saved): ?>
            <div class="cgr-notice-ok">
                <span class="dashicons dashicons-yes-alt" style="color:#00a32a;font-size:18px;width:18px;height:18px"></span>
                Settings saved successfully.
            </div>
        <?php endif; ?>

        <form method="post" action="options.php">
            <?php settings_fields('caffebello_geo_redirect'); ?>

            <div class="cgr-layout">
                <div>
                    <div class="cgr-box">
                        <div class="cgr-box-header">
                            <h2 class="cgr-box-title">
                                <span class="dashicons dashicons-admin-settings"></span>
                                Redirect Settings
                            </h2>
                        </div>
                        <div class="cgr-box-body">
                            <div class="cgr-form-row">
                                <label for="cgr_redirect_scope" class="cgr-label-with-icon">
                                    <span class="dashicons dashicons-admin-home" aria-hidden="true"></span>
                                    <?php esc_html_e('Redirect scope', 'caffebello-geo-redirect'); ?>
                                </label>
                                <select id="cgr_redirect_scope" name="cgr_redirect_scope">
                                    <option value="homepage" <?php selected($redirect_scope, 'homepage'); ?>>
                                        Homepage only (/)
                                    </option>
                                    <option value="all_paths" <?php selected($redirect_scope, 'all_paths'); ?>>
                                        All paths (preserve path + query string)
                                    </option>
                                </select>
                                <p class="cgr-desc">
                                    <strong>Homepage only</strong> redirects visitors when they land on <code>/</code>.
                                    <strong>All paths</strong> redirects on every request (still respects manual language cookies).
                                </p>
                            </div>

                            <div class="cgr-divider"></div>

                            <div class="cgr-form-row">
                                <span class="cgr-label-with-icon" style="display:flex;margin-bottom:6px">
                                    <span class="dashicons dashicons-admin-page" aria-hidden="true"></span>
                                    <?php esc_html_e('Country redirect rules', 'caffebello-geo-redirect'); ?>
                                </span>
                                <p class="cgr-desc" style="margin-top:0">
                                    <?php esc_html_e('First matching rule wins.', 'caffebello-geo-redirect'); ?>
                                    <span class="cgr-desc-inline"><span class="dashicons dashicons-admin-links" aria-hidden="true"></span> <strong><?php esc_html_e('Path', 'caffebello-geo-redirect'); ?></strong></span>
                                    <?php esc_html_e('for same-site language prefixes, or', 'caffebello-geo-redirect'); ?>
                                    <span class="cgr-desc-inline"><span class="dashicons dashicons-admin-site-alt3" aria-hidden="true"></span> <strong><?php esc_html_e('Domain / URL', 'caffebello-geo-redirect'); ?></strong></span>
                                    <?php esc_html_e('to send visitors to another domain.', 'caffebello-geo-redirect'); ?>
                                </p>
                            </div>

                            <div id="cgr-rules" class="cgr-rules">
                                <?php
                                $i = 0;
                                if (empty($rules)) {
                                    $rules = cgr_get_default_rules();
                                }
                                foreach ($rules as $rule) {
                                    cgr_render_rule_row($i, $rule, false);
                                    $i++;
                                }
                                ?>
                                <?php cgr_render_rule_row(9999, ['country' => '', 'target_type' => 'path', 'target' => ''], true); ?>
                            </div>
                        </div>
                        <div class="cgr-submit-row">
                            <button type="submit" class="cgr-btn-primary">
                                <span class="dashicons dashicons-saved" style="font-size:14px;width:14px;height:14px;margin-top:3px"></span>
                                Save Changes
                            </button>
                        </div>
                    </div>

                    <div class="cgr-box">
                        <div class="cgr-box-header">
                            <h2 class="cgr-box-title">
                                <span class="dashicons dashicons-database"></span>
                                GeoIP Database
                            </h2>
                        </div>
                        <div class="cgr-box-body">
                            <div class="cgr-form-row">
                                <label for="cgr_db" class="cgr-label-with-icon">
                                    <span class="dashicons dashicons-database" aria-hidden="true"></span>
                                    <?php esc_html_e('GeoLite2-Country.mmdb path', 'caffebello-geo-redirect'); ?>
                                </label>
                                <input type="text" id="cgr_db" name="cgr_db_path"
                                       value="<?php echo esc_attr($db_path); ?>"
                                       placeholder="<?php echo esc_attr(WP_CONTENT_DIR . '/uploads/GeoLite2-Country.mmdb'); ?>" />
                                <p class="cgr-desc">
                                    Full server path to your database file.<br>
                                    Download free at <a href="https://www.maxmind.com/en/geolite2/signup" target="_blank">maxmind.com</a>
                                    → upload to <code><?php echo esc_html(WP_CONTENT_DIR); ?>/uploads/</code>
                                </p>
                            </div>
                        </div>
                        <div class="cgr-submit-row">
                            <button type="submit" class="cgr-btn-primary">
                                <span class="dashicons dashicons-saved" style="font-size:14px;width:14px;height:14px;margin-top:3px"></span>
                                Save Changes
                            </button>
                        </div>
                    </div>

                    <div class="cgr-box">
                        <div class="cgr-box-header">
                            <h2 class="cgr-box-title">
                                <span class="dashicons dashicons-search"></span>
                                Test GeoIP Lookup
                            </h2>
                        </div>
                        <div class="cgr-box-body">
                            <div class="cgr-ip-row">
                                <div class="cgr-live-dot"></div>
                                <span class="dashicons dashicons-networking" style="color:#646970;font-size:16px;width:16px;height:16px" aria-hidden="true"></span>
                                <span class="cgr-ip-label"><?php esc_html_e('Your current IP:', 'caffebello-geo-redirect'); ?></span>
                                <span class="cgr-ip-val"><?php echo esc_html($ip); ?></span>
                            </div>
                            <p style="font-size:13px;color:#646970;margin:0 0 12px">
                                Run a lookup against the GeoIP database to verify everything is working correctly.
                            </p>
                            <div class="cgr-test-actions">
                                <label for="cgr-test-ip" class="screen-reader-text">IP address</label>
                                <input type="text" id="cgr-test-ip" value="<?php echo esc_attr($ip); ?>" placeholder="203.0.113.10" />
                                <button type="button" class="cgr-btn-secondary" id="cgr-test-ip-btn">
                                    <span class="dashicons dashicons-location-alt" style="font-size:14px;width:14px;height:14px;margin-top:3px"></span>
                                    Run Test
                                </button>
                            </div>
                            <div id="cgr-test-ip-result" style="margin-top:12px"></div>
                        </div>
                    </div>
                </div>

                <div>
                    <div class="cgr-box">
                        <div class="cgr-box-header">
                            <h2 class="cgr-box-title">
                                <span class="dashicons dashicons-performance"></span>
                                System Status
                            </h2>
                        </div>
                        <div class="cgr-box-body">
                            <div class="cgr-status-list">
                                <div class="cgr-status-row">
                                    <div class="cgr-indicator <?php echo $db_ok ? 'ok' : 'err'; ?>"></div>
                                    <span class="cgr-status-name">
                                        <span class="dashicons dashicons-database" aria-hidden="true"></span>
                                        <?php esc_html_e('GeoLite2 database', 'caffebello-geo-redirect'); ?>
                                    </span>
                                    <span class="cgr-status-tag <?php echo $db_ok ? 'ok' : 'err'; ?>">
                                        <?php echo $db_ok ? 'Found' : 'Missing'; ?>
                                    </span>
                                </div>
                                <div class="cgr-status-row">
                                    <div class="cgr-indicator <?php echo $lib_ok ? 'ok' : 'err'; ?>"></div>
                                    <span class="cgr-status-name">
                                        <span class="dashicons dashicons-editor-code" aria-hidden="true"></span>
                                        <?php esc_html_e('GeoIP2 PHP library', 'caffebello-geo-redirect'); ?>
                                    </span>
                                    <span class="cgr-status-tag <?php echo $lib_ok ? 'ok' : 'err'; ?>">
                                        <?php echo $lib_ok ? 'Found' : 'Missing'; ?>
                                    </span>
                                </div>
                                <div class="cgr-status-row">
                                    <div class="cgr-indicator <?php echo $active ? 'ok' : 'warn'; ?>"></div>
                                    <span class="cgr-status-name">
                                        <span class="dashicons dashicons-external" aria-hidden="true"></span>
                                        <?php esc_html_e('Redirect', 'caffebello-geo-redirect'); ?>
                                    </span>
                                    <span class="cgr-status-tag <?php echo $active ? 'ok' : 'warn'; ?>">
                                        <?php echo $active ? 'Active' : 'Inactive'; ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="cgr-box">
                        <div class="cgr-box-header">
                            <h2 class="cgr-box-title">
                                <span class="dashicons dashicons-list-view"></span>
                                Setup Checklist
                            </h2>
                        </div>
                        <div class="cgr-box-body">
                            <div class="cgr-steps">
                                <div class="cgr-step">
                                    <div class="cgr-step-num">1</div>
                                    <div class="cgr-step-text">
                                        Register free at <a href="https://www.maxmind.com/en/geolite2/signup" target="_blank">maxmind.com</a> and download <strong>GeoLite2-Country.mmdb</strong>
                                    </div>
                                </div>
                                <div class="cgr-step">
                                    <div class="cgr-step-num">2</div>
                                    <div class="cgr-step-text">
                                        Upload <code>.mmdb</code> to <code>/wp-content/uploads/</code>
                                    </div>
                                </div>
                                <div class="cgr-step">
                                    <div class="cgr-step-num">3</div>
                                    <div class="cgr-step-text">
                                        Download <a href="https://github.com/maxmind/GeoIP2-php/releases" target="_blank">GeoIP2 PHP library</a> → upload to <code>/wp-content/uploads/geoip2/</code>
                                    </div>
                                </div>
                                <div class="cgr-step">
                                    <div class="cgr-step-num">4</div>
                                    <div class="cgr-step-text">
                                        Click <strong>Run Test</strong> — all indicators should turn green ✓
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="cgr-box">
                        <div class="cgr-box-header">
                            <h2 class="cgr-box-title">
                                <span class="dashicons dashicons-controls-forward"></span>
                                How It Works
                            </h2>
                        </div>
                        <div class="cgr-box-body">
                            <div class="cgr-flow">
                                <div class="cgr-flow-step">
                                    <div class="cgr-flow-icon">👤</div>
                                    <div class="cgr-flow-label">Visitor arrives</div>
                                </div>
                                <div class="cgr-flow-arrow">›</div>
                                <div class="cgr-flow-step">
                                    <div class="cgr-flow-icon">🗄️</div>
                                    <div class="cgr-flow-label">Local DB check</div>
                                </div>
                                <div class="cgr-flow-arrow">›</div>
                                <div class="cgr-flow-step">
                                    <div class="cgr-flow-icon">🌍</div>
                                    <div class="cgr-flow-label">Country = NL?</div>
                                </div>
                                <div class="cgr-flow-arrow">›</div>
                                <div class="cgr-flow-step">
                                    <div class="cgr-flow-icon">↩️</div>
                                    <div class="cgr-flow-label">Redirect /nl</div>
                                </div>
                            </div>
                            <div class="cgr-perf-badge">
                                <span class="dashicons dashicons-bolt" style="font-size:13px;width:13px;height:13px;margin-top:1px"></span>
                                1–5ms · no external API · no JS flicker
                            </div>
                            <div class="cgr-divider"></div>
                            <p style="font-size:12px;color:#646970;margin:0;line-height:1.6">
                                Once redirected, a cookie is set so the user is never forced again.
                                Manual language switches are always respected.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <?php
}

function cgr_ajax_test_geoip()
{
    if (!current_user_can('manage_options')) {
        wp_send_json_error(['message' => 'Unauthorized.'], 403);
    }

    check_ajax_referer('cgr_test_geoip', 'nonce');

    $ip = isset($_POST['ip']) ? sanitize_text_field((string) wp_unslash($_POST['ip'])) : '';
    if ($ip === '') {
        $ip = cgr_get_client_ip();
    }

    $result = cgr_geoip_lookup_country($ip);
    if (is_wp_error($result)) {
        wp_send_json_error(['message' => $result->get_error_message()], 400);
    }

    wp_send_json_success($result);
}
add_action('wp_ajax_cgr_test_geoip', 'cgr_ajax_test_geoip');

// ============================================================
// 4. SERVER-SIDE REDIRECT (TranslatePress-friendly)
// ============================================================
add_action('template_redirect', function () {
    if (is_admin() || wp_doing_ajax()) {
        return;
    }

    $request_uri  = $_SERVER['REQUEST_URI'] ?? '/';
    $request_path = wp_parse_url($request_uri, PHP_URL_PATH) ?: '/';

    $scope = cgr_get_redirect_scope();
    if ($scope === 'homepage' && $request_path !== '/') {
        return;
    }

    // Respect manual language choice from this plugin
    if (isset($_COOKIE['cgr_language']) && $_COOKIE['cgr_language'] === 'manual') {
        return;
    }

    // Respect TranslatePress own language memory (if set)
    if (!empty($_COOKIE['trp_language'])) {
        return;
    }

    $ip = cgr_get_client_ip();
    if ($ip === '') {
        return;
    }

    $rules = cgr_get_redirect_rules();
    if (empty($rules)) {
        return;
    }

    $country_data = cgr_geoip_lookup_country($ip);
    if (is_wp_error($country_data)) {
        return;
    }
    $country = strtoupper((string) ($country_data['code'] ?? ''));

    $matched = null;
    foreach ($rules as $rule) {
        if (!empty($rule['country']) && strtoupper((string) $rule['country']) === $country) {
            $matched = $rule;
            break;
        }
    }
    if (!$matched) {
        return;
    }

    $current_url = home_url($request_uri);
    $destination = cgr_build_destination_for_request($matched, $request_uri);
    if ($destination === '') {
        return;
    }

    if (cgr_would_loop($destination, $current_url)) {
        return;
    }

    $dest_parts = wp_parse_url($destination);
    $is_http    = is_array($dest_parts) && isset($dest_parts['scheme']) && in_array(strtolower((string) $dest_parts['scheme']), ['http', 'https'], true);

    if (!$is_http) {
        return;
    }

    // Auto cookie can still be overwritten by manual switch
    setcookie('cgr_language', 'auto', time() + (86400 * 30), '/');

    $home = wp_parse_url(home_url('/'));
    $dest_host = isset($dest_parts['host']) ? strtolower((string) $dest_parts['host']) : '';
    $home_host = is_array($home) && isset($home['host']) ? strtolower((string) $home['host']) : '';

    if ($home_host !== '' && $dest_host === $home_host) {
        wp_safe_redirect($destination, 302);
    } else {
        wp_redirect($destination, 302);
    }
    exit;
}, 1);

// ============================================================
// 5. AJAX — mark manual language switch
// ============================================================
function cgr_set_manual_handler()
{
    setcookie('cgr_language', 'manual', time() + (86400 * 365), '/');
    wp_send_json_success();
}
add_action('wp_ajax_nopriv_cgr_set_manual', 'cgr_set_manual_handler');
add_action('wp_ajax_cgr_set_manual', 'cgr_set_manual_handler');

// ============================================================
// 6. FRONTEND JS — detect manual switcher clicks
// ============================================================
add_action('wp_footer', function () {
    $rules = cgr_get_redirect_rules();
    $paths = [];
    foreach ($rules as $rule) {
        if (!is_array($rule)) {
            continue;
        }
        if (($rule['target_type'] ?? '') !== 'path') {
            continue;
        }
        $p = isset($rule['target']) ? (string) $rule['target'] : '';
        if ($p === '') {
            continue;
        }
        $p = '/' . ltrim($p, '/');
        $paths[] = $p;
    }

    // Backwards compatibility: include legacy path if present.
    $legacy_path = trim((string) get_option('cgr_target_path', ''));
    if ($legacy_path !== '') {
        $paths[] = '/' . ltrim($legacy_path, '/');
    }

    $paths = array_values(array_unique(array_filter($paths)));
    ?>
    <script>
    (function() {
        var ajaxUrl = '<?php echo esc_js(admin_url('admin-ajax.php')); ?>';
        var paths = <?php echo wp_json_encode($paths); ?>;

        var selectors = [];
        paths.forEach(function(p) {
            selectors.push('a[href*="' + p + '"]');
        });
        selectors.push('a[hreflang]');
        selectors.push('.trp-language-switcher a');
        selectors.push('.wpml-ls-item a');
        selectors.push('.lang-item a');

        document.querySelectorAll(selectors.join(', ')).forEach(function(link) {
            link.addEventListener('click', function() {
                fetch(ajaxUrl + '?action=cgr_set_manual', { credentials: 'same-origin' });
            });
        });
    })();
    </script>
    <?php
});