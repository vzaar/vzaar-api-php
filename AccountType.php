<?php

Class CostType {
    var $currency;
    var $monthly;
    /**
     *
     * @param <string> $currency
     * @param <integer> $monthly
     */
    public function CostType($currency, $monthly) {
	$this->currency = $currency;
	$this->monthly = $monthly;
    }
}

Class RightsType {
    var $borderless;
    var $searchEnhancer;
    public function RightsType($borderless, $searchEnhancer) {
	$this->borderless = $borderless;
	$this->searchEnhancer = $searchEnhancer;
    }
}

class AccountType {
    var $version;
    var $accountId;
    var $title;
    var $cost;
    var $bandwidth;
    var $rights;

    ///////////////////////////////////////////////////////////////////////////

    /**
     * Package protected constructor.
     *
     * @param version the vzaar API version number
     * @param accountId the vzaar account ID
     * @param title the name of the vzaar account
     * @param monthly the monthly cost of the account in the given currency
     * @param currency the currency the account is charged in. Currently this
     * 			is only in dollars
     * @param bandwidth the amount of monthly bandwidth allocated to a user
     * 			for video service and playing
     * @param borderless if the user is allowed to use a player with no skin
     * @param searchEnhancer if the user is allowed to optimize where google
     * 			directs video traffic
     */
    public function AccountType($version, $accountId, $title, $monthly, $currency, $bandwidth, $borderless, $searchEnhancer) {
	$this->version = $version;
	$this->accountId = $accountId;
	$this->title = $title;
	$this->cost = new CostType($currency, $monthly);

	$this->rights = new RightsType($borderless, $searchEnhancer);
	$this->bandwidth = $bandwidth;
    }

    static function fromJson($data) {
	$jo = json_decode($data);
	if ($jo==NULL) {
	    return NULL;
	}
	else {
	//$version, $accountId, $title, $monthly, $currency, $bandwidth, $borderless, $searchEnhancer
	    $acc = new AccountType($jo->version, $jo->account_id, $jo->title, $jo->cost->monthly, $jo->cost->currency, $jo->bandwidth, $jo->rights->borderless, $jo->rights->searchEnhancer);
	    return $acc;
	}
    }
}

?>