<?php
/**
 * This class provides a generic PHP API to Voip.ms services
 */

namespace PaigeJulianne;
class VoipMs
{

    protected string $apiUsername;
    protected string $apiPassword;

    const API_URL = "https://voip.ms/api/v1/rest.php";

    public function __construct($apiUsername, $apiPassword)
    {
        $this->apiUsername = $apiUsername;
        $this->apiPassword = $apiPassword;
    }

    private function _doRequest(string $method, array $parameters = array()): array
    {
        $curl = curl_init();
        $url = sprintf("%s?api_username=%s&api_password=%s", self::API_URL, $this->apiUsername, $this->apiPassword);
        $url .= '&method=' . urlencode($method);
        if (!empty($parameters)) {
            $parameterString = http_build_query($parameters);
            $url = sprintf("%s&%s", $url, $parameterString);
        }
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($curl);
        curl_close($curl);
        $rawData = json_decode($result, true);
        if ($rawData['status'] == 'success') {
            return $rawData;
        } else {
            throw new \Exception($rawData['status']);
        }
    }

    /**
     * Voip.ms methods
     */

    public function addMemberToConference(int $memberId, int $conferenceId): string
    {
        $raw = $this->_doRequest('addMemberToConference', ["member" => $memberId, "conference" => $conferenceId]);
        return $raw['member'];
    }

    public function getBalance(bool $getCallsStatistics = false): array
    {
        if ($getCallsStatistics) {
            $raw = $this->_doRequest('getBalance', ['advanced' => 'True']);
        } else {
            $raw = $this->_doRequest('getBalance');
        }
        return $raw['balance'];
    }

    public function getConference(int $conferenceId): array
    {
        $raw = $this->_doRequest('getConference', ['conference' => $conferenceId]);
        return $raw['conference'];
    }

    public function getConferenceMembers(int $conferenceId): array
    {
        $raw = $this->_doRequest('getMembers', ['conference' => $conferenceId]);
        return $raw['members'];
    }

    public function getConferenceRecordings(int $conferenceId, string $dateFrom = null, string $dateTo = null): array
    {
        if ($dateFrom || $dateTo) {
            $params = ["date_from" => $dateFrom, "date_to" => $dateTo];
        }
        $raw = $this->_doRequest('getConferenceRecordings', $params);
        return $raw['recordings'];
    }

    public function getConferenceRecordingFile(int $conferenceId, string $recordingId): array
    {
        $raw = $this->_doRequest('getConferenceRecordingFile', ['conference' => $conferenceId, 'recording' => $recordingId]);
        return $raw['recording'];
    }

    public function getSequences(int $sequenceId, int $client = 0): array
    {
        if ($client) {
            $params = ['client' => $client];
        }
        $raw = $this->_doRequest('getSequences', $params);
        return $raw['sequences'];
    }

    public function getCountries(string $countryCode): string
    {
        $raw = $this->_doRequest('getCountries', ['country' => $countryCode]);
        return $raw['countries'][0]['description'];
    }

    public function getLanguages(string $languageCode): string
    {
        $raw = $this->_doRequest('getLanguages', ['language' => $languageCode]);
        return $raw['languages'][0]['description'];
    }

    public function getLocales(string $localeCode): string
    {
        $raw = $this->_doRequest('getLocales', ['locale' => $localeCode]);
        return $raw['locales'][0]['description'];
    }

    public function getServersInfo(int $serverId): array
    {
        $raw = $this->_doRequest('getServersInfo', ['server_pop' => $serverId]);
        return $raw['servers'][0];
    }

    public function getIP(): string
    {
        $raw = $this->_doRequest('getIP');
        return $raw['ip'];
    }

    public function getTransactionHistory(string $dateFrom, string $dateTo): array
    {
        $params = ["date_from" => $dateFrom, "date_to" => $dateTo];
        $raw = $this->_doRequest('getTransactionHistory', $params);
        return $raw['transactions'];
    }

    public function createSubAccount(string $username, string $protocol, string $description, string $auth_type,
                                     string $password, string $ip, string $device_type, string $callerid_number, string $canada_routing,
                                     string $lock_international, bool $allow225, string $international_route, string $music_on_hold,
                                     string $language, bool $record_calls, string $allowed_codecs, string $dtmf_mode, string $nat,
                                     bool   $sip_traffic = false, int $max_expiry = 3600, int $rtp_timeout = 60,
                                     int    $rtp_hold_timeout = 600, string $ip_restriction = NULL, bool $enable_ip_restriction = false,
                                     string $pop_restriction = null, bool $enable_pop_restriction = false, bool $send_bye = true,
                                     bool   $transcribe = false, string $transcription_locale = NULL, string $transcription_email = null,
                                     int    $internal_extension = 0, int $internal_voicemail = 0, int $internal_dialtime = 0, int $reseller_client = 0,
                                     int    $reseller_package = 0, string $reseller_nextbilling = NULL, bool $reseller_chargesetup = false,
                                     int    $parking_lot = 0, int $transcription_start_delay = 0, bool $enable_internal_cnam = false, string $internal_cnam = ''): array
    {

        foreach (get_defined_vars() as $var_name => $var_value)
            $parameterArray[$var_name] = $var_value;

        $raw = $this->_doRequest('createSubAccount', $parameterArray);
        return ['id' => $raw['id'], 'account' => $raw['account']];
    }

    public function delSubAccount(int $id): void
    {
        $raw = $this->_doRequest('deleteSubAccount', ['id' => $id]);
    }

    public function getAllowedCodecs(string $codec): array
    {
        $raw = $this->_doRequest('getAllowedCodecs', ['codec' => $codec]);
        return $raw['allowed_codecs'];
    }

    public function getAuthTypes(string $type): array
    {
        $raw = $this->_doRequest('getAuthTypes', ['type' => $type]);
        return $raw['auth_types'];
    }

    public function getDeviceTypes(int $device_type): array
    {
        $raw = $this->_doRequest('getDeviceTypes', ['device_type' => $device_type]);
        return $raw['device_types'];
    }

    public function getDMTFModes(string $dtmf_mode): array
    {
        $raw = $this->_doRequest('getDMTFModes', ['dtmf_mode' => $dtmf_mode]);
        return $raw['dtmf_modes'];
    }

    const INVOICE_RANGE_LAST_MONTH = 1;
    const INVOICE_RANGE_LAST_TWO_MONTHS = 2;
    const INVOICE_RANGE_LAST_THREE_MONTHS = 3;
    const INVOICE_RANGE_CURRENT_MONTH = 4;
    const INVOICE_RANGE_LAST_WEEK = 5;
    const INVOICE_RANGE_CURRENT_WEEK = 6;

    const INVOICE_TYPE_US = 0;
    const INVOICE_TYPE_CA = 1;

    public function getInvoice(string $from, string $to, int $range = self::INVOICE_RANGE_LAST_MONTH, int $type = self::INVOICE_TYPE_US): string
    {
        $raw = $this->_doRequest('getInvoice', ['from' => $from, 'to' => $to, 'type' => $type]);
        return $raw['pdf'];
    }

    public function getLockInternational(int $lock_international): array
    {
        $raw = $this->_doRequest('getLockInternational', ['lock_international' => $lock_international]);
        return $raw['lock_international'];
    }

    public function getMusicOnHold(string $music_on_hold): array
    {
        $raw = $this->_doRequest('getMusicOnHold', ['music_on_hold' => $music_on_hold]);
        return $raw['music_on_hold'];
    }

    const MUSIC_ON_HOLD_SORT_ALPHA = 'alpha';
    const MUSIC_ON_HOLD_SORT_RANDOM = 'random';

    public function setMusicOnHold(string $name, string $description, ?bool $volume = false, ?string $sort = self::MUSIC_ON_HOLD_SORT_RANDOM, string $recordings): void
    {
        foreach (get_defined_vars() as $var_name => $var_value)
            $parameterArray[$var_name] = $var_value;
        $raw = $this->_doRequest('setMusicOnHold', $parameterArray);
    }

    public function delMusicOnHold(string $music_on_hold): void
    {
        $raw = $this->_doRequest('deleteMusicOnHold', ['music_on_hold' => $music_on_hold]);
    }

    public function getNAT(string $nat): array
    {
        $raw = $this->_doRequest('getNAT', ['nat' => $nat]);
        return $raw['nat'];
    }

    public function getProtocols(int|string $protocol): array
    {
        $raw = $this->_doRequest('getProtocols', ['protocol' => $protocol]);
        return $raw['protocols'];
    }

    public function getRegistrationStatus(string $account): array
    {
        $raw = $this->_doRequest('getRegistrationStatus', ['account' => $account]);
        return $raw['registrations'];
    }

    public function getReportEstimatedHoldTime(string $type): array
    {
        $raw = $this->_doRequest('getReportEstimatedHoldTime', ['type' => $type]);
        return $raw['types'];
    }

    public function getRoutes(string $route): array
    {
        $raw = $this->_doRequest('getRoutes', ['route' => $route]);
        return $raw['routes'];
    }

    public function getSubAccounts(string $account = NULL)
    {
        $raw = $this->_doRequest('getSubAccounts', ['account' => $account]);
        return $raw['accounts'];
    }

    public function setSubAccount(string $id, string $description, string $auth_type,
                                  string $password, string $ip, string $device_type, string $callerid_number, string $canada_routing,
                                  string $lock_international, bool $allow225, string $international_route, string $music_on_hold,
                                  string $language, bool $record_calls, string $allowed_codecs, string $dtmf_mode, string $nat,
                                  bool   $sip_traffic = false, int $max_expiry = 3600, int $rtp_timeout = 60,
                                  int    $rtp_hold_timeout = 600, string $ip_restriction = NULL, bool $enable_ip_restriction = false,
                                  string $pop_restriction = '', bool $enable_pop_restriction = false, bool $send_bye = true,
                                  bool   $transcribe = false, string $transcription_locale = NULL, string $transcription_email = null,
                                  int    $internal_extension = 0, int $internal_voicemail = 0, int $internal_dialtime = 0, int $reseller_client = 0,
                                  int    $reseller_package = 0, string $reseller_nextbilling = NULL, bool $reseller_chargesetup = false,
                                  int    $parking_lot = 0, int $transcription_start_delay = 0, bool $enable_internal_cnam = false, string $internal_cnam = ''): void
    {

        foreach (get_defined_vars() as $var_name => $var_value)
            $parameterArray[$var_name] = $var_value;
        $raw = $this->_doRequest('setSubAccount', $parameterArray);
    }

    public function getCallParking(int $callparking): array
    {
        $raw = $this->_doRequest('getCallParking', ['callparking' => $callparking]);
        return $raw['call_hunting'];
    }

    public function setCallParking(int    $callparking = 0, string $name, int $timeout, string $failover,
                                   string $language, string $destination, int $delay, int $blf_lamps): int
    {
        foreach (get_defined_vars() as $var_name => $var_value)
            $parameterArray[$var_name] = $var_value;
        if ($callparking == 0) unset($parameterArray['callparking']);
        $raw = $this->_doRequest('setCallParking', $parameterArray);
        return $raw['callparking'];
    }

    public function delCallParking(int $callparking): void
    {
        $raw = $this->_doRequest('delCallParking', ['callparking' => $callparking]);
    }

    const CALL_TYPE_ALL = 'all';
    const CALL_TYPE_INCOMING = 'incoming';
    const CALL_TYPE_OUTGOING = 'outgoing';

    public function getCallRecordings(string $account, int $start, int $length, string $date_from, string $date_to, string $call_type = self::CALL_TYPE_ALL): array
    {
        foreach (get_defined_vars() as $var_name => $var_value)
            $parameterArray[$var_name] = $var_value;
        $raw = $this->_doRequest('getCallRecordings', $parameterArray);
        return $raw['recordings'];
    }

    public function getCallRecording(string $account, string $callrecording): array
    {
        $raw = $this->_doRequest('getCallRecording', ['account' => $account, 'callrecording' => $callrecording]);
        unset($raw['status']);
        return $raw;
    }

    public function sendCallRecordingEmail(string $account, string $email, string $callrecording)
    {
        $raw = $this->_doRequest('sendCallRecordingEmail', ['account' => $account, 'email' => $email, 'callrecording' => $callrecording]);
        return $raw['msg'];
    }

    public function delCallRecording(string $account, string $callrecording): void
    {
        $raw = $this->_doRequest('delCallRecording', ['account' => $account, 'callrecording' => $callrecording]);
    }

    public function getCallAccounts(string $client): array
    {
        $raw = $this->_doRequest('getCallAccounts', ['client' => $client]);
        return $raw['accounts'];
    }

    public function getCallBilling(): array
    {
        $raw = $this->_doRequest('getCallBilling');
        return $raw['call_billing'];
    }

    public function getCallTypes(string $client): array
    {
        $raw = $this->_doRequest('getCallTypes', ['client' => $client]);
        return $raw['call_types'];
    }

    public function getCDR(string $date_from, string $date_to, bool $answered = true, bool $noanswer = true,
                           bool   $busy = true, bool $failed = true, string $timezone, string $calltype, string $callbilling, string $account): array
    {
        foreach (get_defined_vars() as $var_name => $var_value)
            $parameterArray[$var_name] = $var_value;
        $raw = $this->_doRequest('getCDR', $parameterArray);
        return $raw['cdr'];
    }

    public function getRates(string $package, string $query)
    {
        $raw = $this->_doRequest('getRates', ['package' => $package, 'query' => $query]);
        return $raw['rates'];
    }

    public function getTerminationRates(string $route, string $query)
    {
        $raw = $this->_doRequest('getTerminationRates', ['route' => $route, 'query' => $query]);
        return $raw['rates'];
    }

    public function getResellerCDR(string $date_from, string $date_to, bool $answered = true, bool $noanswer = true,
                                   bool   $busy = true, bool $failed = true, string $timezone, string $calltype, string $callbilling, string $account): array
    {
        foreach (get_defined_vars() as $var_name => $var_value)
            $parameterArray[$var_name] = $var_value;
        $raw = $this->_doRequest('getResellerCDR', $parameterArray);
        return $raw['cdr'];
    }

    public function addCharge(string $client, string $charge, string $description, ?bool $testing = false): void
    {
        foreach (get_defined_vars() as $var_name => $var_value)
            $parameterArray[$var_name] = $var_value;
        $raw = $this->_doRequest('addCharge', $parameterArray);
    }

    public function addPayment(string $client, string $charge, string $description, ?bool $testing = false): void
    {

        foreach (get_defined_vars() as $var_name => $var_value)
            $parameterArray[$var_name] = $var_value;

        $raw = $this->_doRequest('addPayment', $parameterArray);
    }

    public function assignDIDvPRI(string $did, string $vpri): array
    {
        $raw = $this->_doRequest('assignDIDvPRI', ['did' => $did, 'vpri' => $vpri]);
        return ['vpri' => $raw['vpri'], 'DIDAdded' => $raw['DIDAdded'], 'monthly' => $raw['monthly']];
    }

    public function getBalanceManagement(null|string $balance_management = NULL)
    {
        $raw = $this->_doRequest('getBalanceManagement', ['balance_management' => $balance_management]);
        return $raw['balance_management'];
    }

    public function getCharges(string $client): array
    {
        $raw = $this->_doRequest('getCharges', ['client' => $client]);
        return $raw['charges'];
    }

    public function getClientPackages(string $client): array
    {
        $raw = $this->_doRequest('getClientPackages', ['client' => $client]);
        return $raw['packages'];
    }

    public function getClients(?string $client = null): array
    {
        $raw = $this->_doRequest('getClients', ['client' => $client]);
        return $raw['clients'];
    }

    public function getClientThreshold(string $client): array
    {
        $raw = $this->_doRequest('getClientThreshold', ['client' => $client]);
        return $raw['threshold_information'];
    }

    public function getDeposits(string $client): array
    {
        $raw = $this->_doRequest('getDeposits', ['client' => $client]);
        return $raw['deposits'];
    }

    public function getPackages(null|string $package = null): array
    {
        $raw = $this->_doRequest('getPackages', ['package' => $package]);
        return $raw['packages'];
    }

    public function getResellerBalance(string $client): array
    {
        $raw = $this->_doRequest('getResellerBalance', ['client' => $client]);
        return $raw['balance'];
    }

    public function setClient(string  $client, string $email, string $password, ?string $company = null,
                              string  $firstname, string $lastname, ?string $address = null, ?string $city = null,
                              ?string $state = null, ?string $country = null, ?string $zip = null, string $phone_number,
                              string  $balance_management): void
    {

        foreach (get_defined_vars() as $var_name => $var_value)
            $parameterArray[$var_name] = $var_value;

        $raw = $this->_doRequest('setClient', $parameterArray);
    }

    public function setClientThreshold(string $client, string $threshold, ?string $email = null): void
    {
        $raw = $this->_doRequest('setClientThreshold', ['client' => $client, 'threshold' => $threshold, 'email' => $email]);
    }

    public function setConference(int     $conference, string $name, string $description, ?string $members,
                                  int     $max_members, ?string $sound_join, ?string $sound_leave, ?string $sound_has_joined, ?string $sound_has_left,
                                  ?string $sound_kicked, ?string $sound_muted, ?string $sound_unmuted, ?string $sound_only_person,
                                  ?string $sound_only_one, ?string $sound_there_are, ?string $sound_other_in_party, ?string $sound_place_into_conference,
                                  ?string $sound_get_pin, ?string $sound_invalid_pin, ?string $sound_locked, ?string $sound_locked_now,
                                  ?string $sound_unlocked_now, ?string $sound_error_menu, ?string $sound_participants_muted,
                                  ?string $sound_participants_unmuted, ?string $language): int
    {

        foreach (get_defined_vars() as $var_name => $var_value)
            $parameterArray[$var_name] = $var_value;

        $raw = $this->_doRequest('setConference', $parameterArray);
        return $raw['conference'];
    }

    public function setConferenceMember(int     $conference, int $member, string $name,
                                        ?string $description, ?int $pin, ?bool $announce_join_leave, ?bool $admin,
                                        ?bool   $start_muted, ?bool $announce_user_count, ?bool $announce_only_user,
                                        ?string $moh_when_empty, ?bool $quiet, ?string $announcement, ?bool $drop_silence,
                                        ?int    $talking_threshold, ?int $silence_threshold, ?bool $talk_detection, ?bool $jitter_buffer): int
    {
        foreach (get_defined_vars() as $var_name => $var_value)
            $parameterArray[$var_name] = $var_value;

        $raw = $this->_doRequest('setConferenceMember', $parameterArray);
        return $raw['member'];
    }

    public function setSequences(string $sequence, string $name, array $steps): int
    {
        foreach (get_defined_vars() as $var_name => $var_value)
            $parameterArray[$var_name] = $var_value;

        $raw = $this->_doRequest('setSequences', $parameterArray);
        return $raw['sequence'];
    }

    public function signupClient(string $firstname, string $lastname, ?string $company,
                                 string $address, string $city, string $state, string $country, string $zip,
                                 string $phone_number, string $email, string $confirm_email, string $password,
                                 string $confirm_password, ?bool $activate = true, ?string $balance_management): int
    {
        foreach (get_defined_vars() as $var_name => $var_value)
            $parameterArray[$var_name] = $var_value;

        $raw = $this->_doRequest('signupClient', $parameterArray);
        return $raw['client'];
    }


}






