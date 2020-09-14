<?php
    error_reporting(E_ALL);
    set_time_limit(0);
    define('DIR', __DIR__);
    if (isset($_GET['check'])) {
        $inn = $_POST['inn'] ?? null;

        if (!$inn || $inn == '') {
            echo json_encode(['status' => 0, 'ret' => 'не введён ИНН']);
            exit;
        }

        $inn = preg_replace('/[^\d]/', '', $inn);
        $error_message = '';
        if (!validateINN($inn, $error_message)) {
            echo json_encode(['status' => 0, 'ret' => $error_message]);
            exit;
        }

        $check = checkLastINN($inn);
        if ($check !== false) {
            if (!$check['error']) {
                echo json_encode(['status' => 1, 'ret' => $check['message']]);
                exit;
            } else {
                echo json_encode(['status' => 0, 'ret' => $check['message']]);
                exit;
            }
        }

        $request = getRequestJSON([
            "inn" => $inn,
            "requestDate" => date('Y-m-d')
        ]);

        if (isset($request->status) && $request->status !== true) {
            echo json_encode(['status' => 1, 'ret' => $request->message]);
        } else {
            echo json_encode(['status' => 0, 'ret' => $request->message]);
        }

        setLastINN($inn, $request);
        exit;
    }

    function setLastINN($inn, $request) {
        $array = [
            'last_check' => time(),
            'status' => $request->status ?? -1,
            'message' => $request->message ?? ''
        ];
        $fileINN = fopen(DIR . '/checked/' . $inn . '.txt', 'w+');
        fwrite($fileINN, serialize($array));
        fclose($fileINN);
        return ;
    }

    function checkLastINN($inn) {
		if (!is_dir(DIR . '/checked')) {
			mkdir(DIR . '/checked');
			chmod(DIR . '/checked', 0775);
			return false;
		}
		
        if (!is_file(DIR . '/checked/' . $inn . '.txt'))
            return false;

        $fileINN = fopen(DIR . '/checked/' . $inn . '.txt', 'r');
        $f = fread($fileINN, 1024);
        $f = unserialize($f);
        
        if (time() - $f['last_check'] > 86400)
            return false;

        if ($f['status'] == -1)
            return false;

        fclose($fileINN);

        return $f;
    }

    function validateINN($inn, &$error_message = null) {
		$result = false;
		$inn = (string) $inn;
		if (!in_array($inn_length = strlen($inn), [10, 12])) {
			$error_message = 'ИНН может состоять только из 10 или 12 цифр';
		} else {
			$check_digit = function($inn, $coefficients) {
				$n = 0;
				foreach ($coefficients as $i => $k) {
					$n += $k * (int) $inn{$i};
				}
				return $n % 11 % 10;
			};
			switch ($inn_length) {
				case 10:
					$n10 = $check_digit($inn, [2, 4, 10, 3, 5, 9, 4, 6, 8]);
					if ($n10 === (int) $inn{9}) {
						$result = true;
					}
					break;
				case 12:
					$n11 = $check_digit($inn, [7, 2, 4, 10, 3, 5, 9, 4, 6, 8]);
					$n12 = $check_digit($inn, [3, 7, 2, 4, 10, 3, 5, 9, 4, 6, 8]);
					if (($n11 === (int) $inn{10}) && ($n12 === (int) $inn{11})) {
						$result = true;
					}
					break;
			}
			if (!$result) {
				$error_message = 'неверно введён ИНН';
			}
		}
		return $result;
	}

    function getRequestJSON($request) {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, 'https://statusnpd.nalog.ru/api/v1/tracker/taxpayer_status');
		$request = json_encode($request);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $request);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$output = curl_exec($ch);
		curl_close($ch);
		return json_decode($output);
	}
?>
