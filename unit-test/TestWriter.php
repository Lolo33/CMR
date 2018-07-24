<?php

/**
 * Created by PhpStorm.
 * User: Niquelesstup
 * Date: 06/06/2018
 * Time: 01:03
 */
class TestWriter
{

    public function writeTestResult($resultTest){
        if ($resultTest === true)
            return '<div class="bg-success text-center">Validé</div>';
        elseif ($resultTest === false)
            return '<div class="bg-danger text-center">Non validé</div>';
        else
            return '<div class="bg-warning text-center">A Tester</div>';
    }

    public function write($class){
        $classInstance = new $class();
        echo "<tr class='info'>
            <td colspan='4'><strong>".$classInstance->nom."</strong></td>
        </tr>";
        $before = new DateTime();
        $nbTestsValid = 0;
        $nbTestsNonValid = 0;
        foreach (get_class_methods($class) as $testMethod){
            if ($testMethod != "__construct") {
                $before_request = new DateTime();
                $isTestValid = $classInstance->$testMethod();
                if ($isTestValid === true)
                    $nbTestsValid++;
                elseif ($isTestValid === false)
                    $nbTestsNonValid++;
                echo '<tr class="active">
                    <td>' . $testMethod . '</td>
                    <td>' . $this->writeTestResult($isTestValid) . '</td>';
                    $after_request = new DateTime();
                    $time_request = $after_request->diff($before_request);
                    echo '<td>' . (new \DateTime())->format("Y-m-d H:i:s") . '</td>
                    <td>' . $time_request->i . " m " . $time_request->s . 's</td>
                </tr>';
            }
        }
        $after = new DateTime();
        $time = $after->diff($before);
        $countTotalTests = count(get_class_methods($class)) - 1;
        $nbTestWaiting = $countTotalTests - $nbTestsValid - $nbTestsNonValid;
        echo "<tr class='bg-white'>
            <td><strong>Temps d'exécution : ". $time->i . " minutes et " .$time->s ." secondes</strong></td>
            <td colspan='3'>
                <span class='text-info'><strong>" .$countTotalTests."</strong> éffectués, </span>
                <span class='text-success'><strong>" .$nbTestsValid. "</strong> validés, </span>
                <span class='text-warning'><strong>" .$nbTestWaiting. "</strong> à tester,</span>
                <span class='text-danger'><strong>" .$nbTestsNonValid. "</strong> non validés  </span>
            </td>
        </tr>";
    }

}