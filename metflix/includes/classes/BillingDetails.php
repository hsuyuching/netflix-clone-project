<?php
class BillingDetails {
    public static function insertDetails($con, $agreement, $token, $username) {
        $query = $con->prepare("INSERT INTO billingDetails (username, agreementId, nextBillingDate, token) 
                                VALUES(:username, :agreementId, :nextBillingDate, :token)");
        $agreementDetails = $agreement->getAgreementDetails();
        $query->bindValue(":username", $username);
        $query->bindValue(":agreementId", $agreement->getId());
        $query->bindValue(":nextBillingDate", $agreementDetails->getNextBillingDate());
        $query->bindValue(":token", $token);

        return $query->execute();
    }
}
?>