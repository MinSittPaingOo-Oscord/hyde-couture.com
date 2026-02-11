<?php 

include "../connection/connectdb.php";

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include './layout/login_error_message.php';
include './logInCheck.php';

$queryPaymentStatus = "SELECT * FROM paymentstatus";
$resultPaymentStatus = $conn->query($queryPaymentStatus);

$queryTrackingStatus = "SELECT * FROM trackingstatus";
$resultTrackingStatus = $conn->query($queryTrackingStatus);

$queryOrderStatus = "SELECT * FROM orderstatus";
$resultOrderStatus = $conn->query($queryOrderStatus);

$queryPaymentType = "SELECT * FROM paymenttype";
$resultPaymentType = $conn->query($queryPaymentType);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Non Registered Customer</title>
    <?php include "./layout/header.php"; ?>
    <style>
 

        .page-header {
            background: linear-gradient(135deg, #004d00, #002600);
            color: white;
            padding: 20px 25px;
            border-radius: 0;
            margin: 0 0 20px 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
        }

        .page-header h1 {
            margin: 0;
            font-size: 1.6rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .selection-form {
            background: white;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
            border-radius: 0;
            padding: 30px;
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .selection-form label {
            font-weight: 500;
            color: #004d00;
            font-size: 1rem;
            text-transform: uppercase;
            letter-spacing: 0.8px;
        }

        .selection-form select,
        .selection-form input[type="text"],
        .selection-form textarea {
            background: #f8f9fa;
            border: 1px solid #e0f0e0;
            color: #333;
            padding: 12px 16px;
            border-radius: 0;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .selection-form select {
            background: #006400;           /* Rolex dark green */
            color: #fff;
            border: 0px solid #b8860b;     /* Gold border */
            appearance: none;
            background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' width='12' height='8' viewBox='0 0 12 8'%3e%3cpath fill='%23b8860b' d='M0 0l6 8 6-8z'/%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right 16px center;
            cursor: pointer;
        }

        .selection-form select:hover,
        .selection-form select:focus,
        .selection-form input[type="text"]:hover,
        .selection-form input[type="text"]:focus,
        .selection-form textarea:hover,
        .selection-form textarea:focus {
            border-color: #b8860b;
            outline: none;
            box-shadow: 0 0 0 3px rgba(184, 134, 11, 0.3); /* Gold glow */
        }

        .selection-form select option {
            background: #e6f3e6;           /* Light green background */
            color: #004d00;
            font-weight: 600;
        }

        .selection-form select option:checked,
        .selection-form select option:hover {
            background: #006400;
            color: #b8860b;                /* Gold text when selected/hovered */
        }

        .selection-form input[type="text"],
        .selection-form textarea {
            background: white;
        }

        .selection-form textarea {
            resize: vertical;
            min-height: 80px;
        }

        .selection-form button {
            background: linear-gradient(45deg, #004d00, #006600);
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 0;
            font-size: 1rem;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            cursor: pointer;
            transition: all 0.3s ease;
            align-self: flex-start;
        }

        .selection-form button:hover {
            background: linear-gradient(45deg, #006600, #008000);
            transform: translateY(-1px);
            box-shadow: 0 3px 8px rgba(0,102,0,0.3);
        }

        .btn-back {
            background: #f8f9fa;
            color: #004d00;
            padding: 8px 16px;
            text-decoration: none;
            font-weight: 500;
            border: 1px solid #e0f0e0;
            cursor: pointer;
        }

        .btn-back:hover {
            background: #e6f7e6;
        }

        @media (max-width: 768px) {
            .main-content {
                padding: 15px;
            }

            .page-header {
                flex-direction: column;
                text-align: center;
            }

            .selection-form {
                padding: 20px;
            }

            .selection-form button {
                width: 100%;
                align-self: stretch;
            }
        }
    </style>
</head>
<body>
    <?php
        include "nav.php";
        $login = $_SESSION['login'] ?? false;  
        if($login == true) {
            echo "<div class='main-content'>";
            
            echo "<div class='page-header'>";
            echo "<h1>Configure Order Details</h1>";
            echo "</div>";

            echo "<form class='selection-form' method='POST' action='non_registered_customer_step2.php' id='signup-form'>";

                echo "<label for='customer_name'>Customer Name</label>";
                echo "<input type='text' id='customer_name' name='customer_name' required>";
                
                echo "<label for='note'>Note</label>";
                echo "<textarea id='note' name='note' rows='3' placeholder='Enter your note'></textarea>";

                echo "<label for='country'>Country</label>";
                echo "<select id='country' name='country' required>";
                echo "<option value=''>Choose your country</option>";
                echo "</select>";

                echo "<label for='street'>Street</label>";
                echo "<input type='text' id='street' placeholder='Enter your street' name='street' required>";

                echo "<label for='city'>City</label>";
                echo "<select id='city' name='city' required>";
                echo "<option value=''>Choose your city</option>";
                echo "</select>";

                echo "<label for='township'>Township</label>";
                echo "<input type='text' id='township' placeholder='Enter your township' name='township' required>";

                echo "<label for='state'>State</label>";
                echo "<input type='text' id='state' placeholder='Enter your state' name='state' required>";

                echo "<label for='postal'>Postal Code</label>";
                echo "<input type='text' id='postal' placeholder='Enter your postal code' name='postalCode' required>";

                echo "<label for='complete_address'>Complete Address</label>";
                echo "<textarea id='complete_address' rows='3' placeholder='Enter the complete address' name='completeAddress' required></textarea>";

                echo "<label for='google_link'>Google Map Link</label>";
                echo "<input type='text' id='google_link' placeholder='Enter the map link (Optional)' name='mapLink'>";
        

                    echo "<label for='paymentStatus'>Payment Status</label>";
                    echo "<select name='paymentStatus' id='paymentStatus' required>";
                    while($rowPaymentStatus = $resultPaymentStatus->fetch_assoc()) {
                        echo "<option value='" . htmlspecialchars($rowPaymentStatus['paymentStatusID']) . "'>" . htmlspecialchars($rowPaymentStatus['paymentStatus']) . "</option>";
                    }
                    echo "</select>";

                    echo "<label for='orderStatus'>Order Status</label>";
                    echo "<select name='orderStatus' id='orderStatus' required>";
                    while($rowOrderStatus = $resultOrderStatus->fetch_assoc()) {
                        echo "<option value='" . htmlspecialchars($rowOrderStatus['orderStatusID']) . "'>" . htmlspecialchars($rowOrderStatus['orderStatus']) . "</option>";
                    }
                    echo "</select>";

                    echo "<label for='trackingStatus'>Tracking Status</label>";
                    echo "<select name='trackingStatus' id='trackingStatus' required>";
                    while($rowTrackingStatus = $resultTrackingStatus->fetch_assoc()) {
                        echo "<option value='" . htmlspecialchars($rowTrackingStatus['trackingStatusID']) . "'>" . htmlspecialchars($rowTrackingStatus['trackingStatus']) . "</option>";
                    }
                    echo "</select>";

                    echo "<label for='paymentType'>Payment Type</label>";
                    echo "<select name='paymentType' id='paymentType' required>";
                    while($rowPaymentType = $resultPaymentType->fetch_assoc()) {
                        echo "<option value='" . htmlspecialchars($rowPaymentType['paymentTypeID']) . "'>" . htmlspecialchars($rowPaymentType['paymentType']) . "</option>";
                    }
                    echo "</select>";

                    echo "<label for='paymentValid'>Payment Valid</label>";
                    echo "<select name='paymentValid' id='paymentValid' required>";
                    echo "<option value='1'>Can pay now</option>";
                    echo "<option value='0'>Cannot pay anymore</option>";
                    echo "</select>";

                    echo "<div style='display: flex; gap: 15px; margin-top: 10px; align-items: center;'>";
                    echo "<button type='submit'>Continue</button>";
            echo "</div>";
            echo "</form>";


            echo "</div>"; // .main-content
        }        
    ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const myanmarCities = [
                {'name_en': 'Yangon'}, {'name_en': 'Mandalay'}, {'name_en': 'Naypyidaw'}, {'name_en': 'Mawlamyine'}, {'name_en': 'Bago'},
                {'name_en': 'Pathein'}, {'name_en': 'Monywa'}, {'name_en': 'Sittwe'}, {'name_en': 'Taunggyi'}, {'name_en': 'Myitkyina'},
                {'name_en': 'Dawei'}, {'name_en': 'Hpa-An'}, {'name_en': 'Myeik'}, {'name_en': 'Hakha'}, {'name_en': 'Loikaw'},
                {'name_en': 'Magway'}, {'name_en': 'Myingyan'}, {'name_en': 'Pakokku'}, {'name_en': 'Yenangyaung'}, {'name_en': 'Thayetmyo'},
                {'name_en': 'Pyay'}, {'name_en': 'Chauk'}, {'name_en': 'Mogok'}, {'name_en': 'Nyaunglebin'}, {'name_en': 'Shwebo'},
                {'name_en': 'Sagaing'}, {'name_en': 'Taungoo'}, {'name_en': 'Myaungmya'}, {'name_en': 'Pyu'}, {'name_en': 'Kyaukpadaung'},
                {'name_en': 'Nyaung-U'}, {'name_en': 'Allanmyo'}, {'name_en': 'Thanlyin'}, {'name_en': 'Twantay'}, {'name_en': 'Kyauktan'},
                {'name_en': 'Bogale'}, {'name_en': 'Pyapon'}, {'name_en': 'Kyaiklat'}, {'name_en': 'Maubin'}, {'name_en': 'Nyaungdon'},
                {'name_en': 'Dedaye'}, {'name_en': 'Kyaukpyu'}, {'name_en': 'Thandwe'}, {'name_en': 'Toungup'}, {'name_en': 'Gwa'},
                {'name_en': 'Manaung'}, {'name_en': 'Kyeintali'}, {'name_en': 'Minbya'}, {'name_en': 'Mrauk-U'}, {'name_en': 'Pauktaw'},
                {'name_en': 'Myebon'}, {'name_en': 'Ann'}, {'name_en': 'Buthidaung'}, {'name_en': 'Maungdaw'}, {'name_en': 'Kyauktaw'},
                {'name_en': 'Ponnagyun'}, {'name_en': 'Rathedaung'}, {'name_en': 'Kawthaung'}, {'name_en': 'Bokpyin'}, {'name_en': 'Yebyu'},
                {'name_en': 'Launglon'}, {'name_en': 'Thayetchaung'}, {'name_en': 'Tanintharyi'}, {'name_en': 'Kyunsu'}, {'name_en': 'Myitta'},
                {'name_en': 'Kawkareik'}, {'name_en': 'Myawaddy'}, {'name_en': 'Kyeikdon'}, {'name_en': 'Kyeikmaraw'}, {'name_en': 'Hlaingbwe'} , 
                {'name_en' : 'Other'}
            ];

            const thailandCities = [
                {'name_en': 'Bangkok'}, {'name_en': 'Samut Prakan'}, {'name_en': 'Nonthaburi'}, {'name_en': 'Pathum Thani'}, {'name_en': 'Phra Nakhon Si Ayutthaya'},
                {'name_en': 'Ang Thong'}, {'name_en': 'Loburi'}, {'name_en': 'Sing Buri'}, {'name_en': 'Chai Nat'}, {'name_en': 'Saraburi'},
                {'name_en': 'Chon Buri'}, {'name_en': 'Rayong'}, {'name_en': 'Chanthaburi'}, {'name_en': 'Trat'}, {'name_en': 'Chachoengsao'},
                {'name_en': 'Prachin Buri'}, {'name_en': 'Nakhon Nayok'}, {'name_en': 'Sa Kaeo'}, {'name_en': 'Nakhon Ratchasima'}, {'name_en': 'Buri Ram'},
                {'name_en': 'Surin'}, {'name_en': 'Si Sa Ket'}, {'name_en': 'Ubon Ratchathani'}, {'name_en': 'Yasothon'}, {'name_en': 'Chaiyaphum'},
                {'name_en': 'Amnat Charoen'}, {'name_en': 'Bueng Kan'}, {'name_en': 'Nong Bua Lam Phu'}, {'name_en': 'Khon Kaen'}, {'name_en': 'Udon Thani'},
                {'name_en': 'Loei'}, {'name_en': 'Nong Khai'}, {'name_en': 'Maha Sarakham'}, {'name_en': 'Roi Et'}, {'name_en': 'Kalasin'},
                {'name_en': 'Sakon Nakhon'}, {'name_en': 'Nakhon Phanom'}, {'name_en': 'Mukdahan'}, {'name_en': 'Chiang Mai'}, {'name_en': 'Lamphun'},
                {'name_en': 'Lampang'}, {'name_en': 'Uttaradit'}, {'name_en': 'Phrae'}, {'name_en': 'Nan'}, {'name_en': 'Phayao'},
                {'name_en': 'Chiang Rai'}, {'name_en': 'Mae Hong Son'}, {'name_en': 'Nakhon Sawan'}, {'name_en': 'Uthai Thani'}, {'name_en': 'Kamphaeng Phet'},
                {'name_en': 'Tak'}, {'name_en': 'Sukhothai'}, {'name_en': 'Phitsanulok'}, {'name_en': 'Phichit'}, {'name_en': 'Phetchabun'},
                {'name_en': 'Ratchaburi'}, {'name_en': 'Kanchanaburi'}, {'name_en': 'Suphan Buri'}, {'name_en': 'Nakhon Pathom'}, {'name_en': 'Samut Sakhon'},
                {'name_en': 'Samut Songkhram'}, {'name_en': 'Phetchaburi'}, {'name_en': 'Prachuap Khiri Khan'}, {'name_en': 'Nakhon Si Thammarat'}, {'name_en': 'Krabi'},
                {'name_en': 'Phangnga'}, {'name_en': 'Phuket'}, {'name_en': 'Surat Thani'}, {'name_en': 'Ranong'}, {'name_en': 'Chumphon'},
                {'name_en': 'Songkhla'}, {'name_en': 'Satun'}, {'name_en': 'Trang'}, {'name_en': 'Phatthalung'}, {'name_en': 'Pattani'},
                {'name_en': 'Yala'}, {'name_en': 'Narathiwat'}, {'name_en' : 'Other'}
            ];

            const countrySelect = document.getElementById('country');
            const citySelect = document.getElementById('city');

            const countries = ['Myanmar', 'Thailand'];
            countries.forEach(c => {
                const opt = document.createElement('option');
                opt.value = c;
                opt.textContent = c;
                countrySelect.appendChild(opt);
            });

            const populateCities = () => {
                const country = countrySelect.value;
                citySelect.innerHTML = '<option value="">Choose your city</option>'; // Reset cities

                let cities = [];
                if (country === 'Myanmar') {
                    cities = myanmarCities.map(city => city.name_en);
                } else if (country === 'Thailand') {
                    cities = thailandCities.map(city => city.name_en);
                }

                cities.forEach(city => {
                    const opt = document.createElement('option');
                    opt.value = city;
                    opt.textContent = city;
                    citySelect.appendChild(opt);
                });
                
                updateCompleteAddress();
            };

            countrySelect.addEventListener('change', populateCities);

            const addressFields = ['street', 'township', 'state', 'postal', 'country', 'city'];
            
            function updateCompleteAddress() {
                const street = document.getElementById('street')?.value || '';
                const township = document.getElementById('township')?.value || '';
                const state = document.getElementById('state')?.value || '';
                const postal = document.getElementById('postal')?.value || '';
                const country = document.getElementById('country')?.value || '';
                const city = document.getElementById('city')?.value || '';
                
                let parts = [];
                
                if (street) parts.push(street);
                if (township) parts.push(township);
                if (state) parts.push(state);
                if (city) parts.push(city);
                if (country) parts.push(country);
                
                let completeAddress = parts.join(', ');
                
                if (postal) {
                    completeAddress += (completeAddress ? ' ' : '') + postal;
                }
                
                const completeAddrElem = document.getElementById('complete_address');
                if (completeAddrElem) {
                    completeAddrElem.value = completeAddress;
                }
            }

            addressFields.forEach(field => {
                const element = document.getElementById(field);
                if (element) {
                    element.addEventListener('change', updateCompleteAddress);
                    if (element.tagName.toLowerCase() !== 'select') {
                        element.addEventListener('input', updateCompleteAddress);
                    }
                }
            });

            document.getElementById('signup-form').addEventListener('submit', function(e) {
                console.log('Form submitted!');
            });
        });
    </script>
</body>
</html>