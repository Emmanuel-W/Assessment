<?php
$data_json = '{
 "region": {
     "name": "Africa",
     "avgAge": 19.7,
     "avgDailyIncomeInUSD": 5,
     "avgDailyIncomePopulation": 0.71
 },
 "periodType": "days",
 "timeToElapse": 58,
 "reportedCases": 674,
 "population": 66622705,
 "totalHospitalBeds": 1380614
}';



function currentlyInfected($reportedCases)
{
  $value=$reportedCases * 10;
  return (int)$value;
}

function currentlyInfectedSevereImpact($reportedCases)
{
  $value=$reportedCases * 50;
  return (int)$value;
}

function infectionsByRequestedTime($currentlyInfected, $periodType)
{
  switch ($periodType){
    case "days":
          $value=$currentlyInfected * (2**0.3214) * 1;
    case "weeks":
          $value=$currentlyInfected * (2**0.3214) * 7;
    case "months":
          $value=$currentlyInfected * (2**0.3214) * 30;
  }
  return (int)$value;
}

function infectionsByRequestedTimeSevereImpact($currentlyInfectedSevereImpact, $periodType)
{
  switch ($periodType){
    case "days":
          $value=$currentlyInfectedSevereImpact * (2**0.3214) * 1;
    case "weeks":
          $value=$currentlyInfectedSevereImpact * (2**0.3214) * 7;
    case "months":
          $value=$currentlyInfectedSevereImpact * (2**0.3214) * 30;
  }
  return (int)$value;
}

function severeCasesByRequestedTime($infectionsByRequestedTime)
{
  $value=($infectionsByRequestedTime * 15)/100;
  return (int)$value;
}

function severeCasesByRequestedTimeSevereImpact($infectionsByRequestedTimeSevereImpact)
{
  $value=($infectionsByRequestedTimeSevereImpact * 15)/100;
  return (int)$value;
}

function hospitalBedsByRequestedTime($severeCasesByRequestedTime, $totalHospitalBeds)
{
  $value = $totalHospitalBeds - (0.35*$severeCasesByRequestedTime);
  return (int)$value;
}

function hospitalBedsByRequestedTimeSevereImpact($severeCasesByRequestedTimeSevereImpact, $totalHospitalBeds)
{
  $value = $totalHospitalBeds - (0.35*$severeCasesByRequestedTimeSevereImpact);
  return (int)$value;
}

function casesForICUByRequestedTime($infectionsByRequestedTime)
{
  $value = $infectionsByRequestedTime * 0.05;
  return (int)$value;
}

function casesForICUByRequestedTimeSevereImpact($infectionsByRequestedTimeSevereImpact)
{
  $value = $infectionsByRequestedTimeSevereImpact * 0.05;
  return (int)$value;
}

function casesForVentilatorsByRequestedTime($infectionsByRequestedTime)
{
  $value = $infectionsByRequestedTime * 0.02;
  return (int)$value;
}

function casesForVentilatorsByRequestedTimeSevereImpact($infectionsByRequestedTimeSevereImpact)
{
  $value = $infectionsByRequestedTimeSevereImpact * 0.02;
  return (int)$value;
}

function dollarsInFlight($infectionsByRequestedTime)
{
  $value = $infectionsByRequestedTime * 0.65 * 1.5 * 30;
  return round($value,2);
  //return $value;

}

function dollarsInFlightSevereImpact($infectionsByRequestedTimeSevereImpact)
{
  $value = $infectionsByRequestedTimeSevereImpact * 0.65 * 1.5 * 30;
  return round($value,2);

}


function covid19ImpactEstimator($data)
{
  $data1 = json_decode($data, true);
  $data = array(
    'data' => $data1,
    'impact' => array(
      'currentlyInfected'=>currentlyInfected($data1['reportedCases']),
      'infectionsByRequestedTime'=>infectionsByRequestedTime(currentlyInfected($data1['reportedCases']),$data1['periodType']),
      'severeCasesByRequestedTime'=>severeCasesByRequestedTime(infectionsByRequestedTime(currentlyInfected($data1['reportedCases']),$data1['periodType'])),
      'hospitalBedsByRequestedTime'=>hospitalBedsByRequestedTime(severeCasesByRequestedTime(infectionsByRequestedTime(currentlyInfected($data1['reportedCases']),$data1['periodType'])),$data1['totalHospitalBeds']),
      'casesForICUByRequestedTime'=>casesForICUByRequestedTime(infectionsByRequestedTime(currentlyInfected($data1['reportedCases']),$data1['periodType'])),
      'casesForVentilatorsByRequestedTime'=>casesForVentilatorsByRequestedTime(infectionsByRequestedTime(currentlyInfected($data1['reportedCases']),$data1['periodType'])),
      'dollarsInFlight'=>dollarsInFlight(infectionsByRequestedTime(currentlyInfected($data1['reportedCases']),$data1['periodType'])),
    ),
    'severeImpact' => array(
      'currentlyInfected'=>currentlyInfectedSevereImpact($data1['reportedCases']),
      'infectionsByRequestedTime'=>infectionsByRequestedTimeSevereImpact(currentlyInfectedSevereImpact($data1['reportedCases']),$data1['periodType']),
      'severeCasesByRequestedTime'=>severeCasesByRequestedTimeSevereImpact(infectionsByRequestedTimeSevereImpact(currentlyInfectedSevereImpact($data1['reportedCases']),$data1['periodType'])),
      'hospitalBedsByRequestedTime'=>hospitalBedsByRequestedTimeSevereImpact(severeCasesByRequestedTimeSevereImpact(infectionsByRequestedTimeSevereImpact(currentlyInfectedSevereImpact($data1['reportedCases']),$data1['periodType'])),$data1['totalHospitalBeds']),
      'casesForICUByRequestedTime'=>casesForICUByRequestedTimeSevereImpact(infectionsByRequestedTimeSevereImpact(currentlyInfectedSevereImpact($data1['reportedCases']),$data1['periodType'])),
      'casesForVentilatorsByRequestedTime'=>casesForVentilatorsByRequestedTimeSevereImpact(infectionsByRequestedTimeSevereImpact(currentlyInfectedSevereImpact($data1['reportedCases']),$data1['periodType'])),
      'dollarsInFlight'=>dollarsInFlightSevereImpact(infectionsByRequestedTimeSevereImpact(currentlyInfectedSevereImpact($data1['reportedCases']),$data1['periodType'])),
    )
  );
  return $data;
}

covid19ImpactEstimator($data_json);
