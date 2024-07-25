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

    public function __construct($apiUsername, $apiPassword) {
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

    public function addMemberToConference(int $memberId, int $conferenceId): string {
        $raw = $this->_doRequest('addMemberToConference', ["member" => $memberId, "conference" => $conferenceId]);
        return $raw['member'];
    }

    public function getBalance(bool $getCallsStatistics = false): array {
        if ($getCallsStatistics) {
            $raw = $this->_doRequest('getBalance', ['advanced' => 'True']);
        } else {
            $raw = $this->_doRequest('getBalance');
        }
        return $raw['balance'];
    }

    public function getConference(int $conferenceId): array {
        $raw = $this->_doRequest('getConference', ['conference' => $conferenceId]);
        return $raw['conference'];
    }

    public function getConferenceMembers(int $conferenceId): array {
        $raw = $this->_doRequest('getMembers', ['conference' => $conferenceId]);
        return $raw['members'];
    }

    public function getConferenceRecordings(int $conferenceId, string $dateFrom = null, string $dateTo = null): array {
        if ($dateFrom || $dateTo) {
            $params = ["date_from" => $dateFrom, "date_to" => $dateTo];
        }
        $raw = $this->_doRequest('getConferenceRecordings', $params);
        return $raw['recordings'];
    }

    public function getConferenceRecordingFile(int $conferenceId, string $recordingId): array {
        $raw = $this->_doRequest('getConferenceRecordingFile', ['conference' => $conferenceId, 'recording' => $recordingId]);
        return $raw['recording'];
    }

    public function getSequences(int $sequenceId, int $client = 0): array {
        if ($client) {
            $params = ['client' => $client];
        }
        $raw = $this->_doRequest('getSequences', $params);
        return $raw['sequences'];
    }

    public function getCountries(string $countryCode): string {
        $raw = $this->_doRequest('getCountries', ['country' => $countryCode]);
        return $raw['countries'][0]['description'];
    }

    public function getLanguages(string $languageCode): string {
        $raw = $this->_doRequest('getLanguages', ['language' => $languageCode]);
        return $raw['languages'][0]['description'];
    }

    public function getLocales(string $localeCode): string {
        $raw = $this->_doRequest('getLocales', ['locale' => $localeCode]);
        return $raw['locales'][0]['description'];
    }

    public function getServersInfo(int $serverId): array {
        $raw = $this->_doRequest('getServersInfo', ['server_pop' => $serverId]);
        return $raw['servers'][0];
    }

    public function getIP(): string {
        $raw = $this->_doRequest('getIP');
        return $raw['ip'];
    }

    public function getTransactionHistory(string $dateFrom, string $dateTo): array {
        $params = ["date_from" => $dateFrom, "date_to" => $dateTo];
        $raw = $this->_doRequest('getTransactionHistory', $params);
        return $raw['transactions'];
    }


}
