<?php
include '../connection/connectdb.php';
$currentPage = 'register.php';
include '../layout/nav.php';
?>

<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>User Registration</title>
    <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css' rel='stylesheet' integrity='sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN' crossorigin='anonymous'>
    <link href='https://fonts.googleapis.com/css2?family=Cinzel:wght@400;700&family=Sancreek&family=Vollkorn:ital,wght@0,400;0,700;1,400&display=swap' rel='stylesheet'>
    <style>

        html, body {
            width: 100%;
            height: 100%;
            margin: 0;
        }

        body {
            background: none;
            background-color: #ffffff;
        }

        .register-container {
            background-image: none;
        }

        .register_heading-font {
            font-family: 'Cinzel', serif;
            font-weight: 700;
            font-size : 15px;
        }
        
        .register_text-font,
        .form-control,
        .form-select,
        .form-check-label,
        .register_login-link {
            font-family: 'Vollkorn', serif;
        }

        .register_rolex-green-bg {
            background-color: #005A2B !important; 
        }

        .register-btn {
            background: linear-gradient(to right, #003c1f, #00c167);
            border: none;
            color: white;
            font-family: 'Cinzel', serif;
            font-weight: 700;
        }
        .register-btn:hover {
            background: linear-gradient(to right, #003d33, #00695c);
            color: white;
        }
        .register_login-link {
            color: #004D40;
            font-weight: 600;
        }

        .register_registration-container {
            background-size: cover;
            display: flex;
            flex-direction: column;
            justify-content: center; 
            align-items: center;
            padding: 0px 0px 0px 0px;
            margin : 0px 0px 0px 0px;
            width : 100% !important;
        }
        .register_form-box {
            background: white;
            border-radius: 0px;
            width: 100% !important;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
        }
        
        .form-control, .form-select {
            border-radius: 0px;
            padding: 10px 15px;
            font-size: 0.95rem;
            height: auto; 
        }
        .form-group label {
            display: none;
        }
        .form-group {
            margin-bottom: 0.5rem; 
        }

 
        
        .register_form-box {
            max-width: 100% !important; 
            width: 100% !important; 
        }


        @media (min-width: 768px) {
            .register_registration-container {
                justify-content: flex-start; 
                padding-top: 0px;
                padding-bottom: 0px;
                padding-left: 30px; 
                padding-right: 30px;
            }

            .register_form-box {
                max-width: 100%; 
                width: 100%; 
            }

            .form-group {
                margin-bottom: 0.8rem;
            }
        }

        @media (min-width: 1200px) {
            .register_form-box {
                max-width: 1200px; 
                width: 100%;
            }
            
        }

    </style>
</head>
<body>
<div class="register_registration-container container-fluid p-0 d-flex">

    <div class='register_form-box shadow-lg' id='signup-box'>
            
            <div class='register_header-compartment register_rolex-green-bg text-white py-3 mb-4'>
                <h4 class='text-center register_heading-font m-0'>Register</h4>
            </div>
                
            <form id='signup-form' class='px-4 pb-4' action="./register_action.php" method="POST" enctype="multipart/form-data">
                <div class='row g-3'>

                    <div class='col-12 col-md-6 form-group'>
                        <label class='form-label register_text-font' for='profile'>Profile</label>
                        <input type='file' class='form-control register_text-font' id='profile' placeholder='Choose your profile photo' required name="profile" accept='*'>
                    </div>

                        <div class='col-12 col-md-6 form-group'>
                            <label class='form-label register_text-font' for='signup-name'>Full Name</label>
                            <input type='text' class='form-control register_text-font' id='signup-name' placeholder='Enter your name' required name="name">
                        </div>
                        
                        <div class='col-12 col-md-6 form-group'>
                            <label class='form-label register_text-font' for='signup-email'>Email</label>
                            <input type='email' class='form-control register_text-font' id='signup-email' placeholder='Email' required name="email">
                        </div>

                        <div class='col-12 col-md-6 form-group'>
                            <label class='form-label register_text-font' for='signup-birthday'>Birthday</label>
                            <input type='date' class='form-control register_text-font' id='signup-birthday' placeholder='mm/dd/yy' required name="birthday">
                        </div>

                        <div class='col-12 col-md-6 form-group'>
                            <label class='form-label register_text-font' for='phone'>Phone Number</label>
                            <input type='text' class='form-control register_text-font' id='phone' placeholder='Enter your phone number' required name="phoneNumber">
                        </div>
                        
                        <div class='col-12 col-md-6 form-group'>
                            <label class='form-label register_text-font' for='signup-password'>Passcode</label>
                            <input type='password' class='form-control register_text-font' id='signup-password' placeholder='Enter your passcode' required name="passcode">
                        </div>
                        
                        <div class='col-12 col-md-6 form-group'>
                            <label class='form-label register_text-font' for='signup-password'>Passcode Again</label>
                            <input type='password' class='form-control register_text-font' id='signup-password' placeholder='Confirm passcode' required name="confirmPasscode">
                        </div>

                        <div class='col-12 col-md-6 form-group'>
                            <label class='form-label register_text-font' for='country'>Country</label>
                            <select class='form-select register_text-font' id='country' required name="country">
                                <option value=''>choose your country</option>
                            </select>
                        </div>
                        
                        <div class='col-12 col-md-6 form-group'>
                            <label class='form-label register_text-font' for='street'>Street</label>
                            <input type='text' class='form-control register_text-font' id='street' placeholder='Enter your street' required name="street">
                        </div>
                        
                        <div class='col-12 col-md-6 form-group'>
                            <label class='form-label register_text-font' for='city'>City</label>
                            <select class='form-select register_text-font' id='city' required name="city">
                                <option value=''>Choose your city</option>
                            </select>
                        </div>
                        
                        <div class='col-12 col-md-6 form-group'>
                            <label class='form-label register_text-font' for='township'>Township</label>
                            <input type='text' class='form-control register_text-font' id='township' placeholder='Enter your township' required name="township">
                        </div>

                        <div class='col-12 col-md-6 form-group'>
                            <label class='form-label register_text-font' for='state'>State</label>
                            <input type='text' class='form-control register_text-font' id='state' placeholder='Enter your state' required name="state">
                        </div>
                        
                        <div class='col-12 col-md-6 form-group'>
                            <label class='form-label register_text-font' for='postal'>Postal Code</label>
                            <input type='text' class='form-control register_text-font' id='postal' placeholder='Enter your postal code' required name="postalCode">
                        </div>
                        
                        <div class='col-12 form-group'>
                            <label class='form-label register_text-font' for='complete_address'>Complete Address</label>
                            <textarea class='form-control register_text-font' id='complete_address' rows='3' placeholder='Enter the complete address' required name="completeAddress"></textarea>
                        </div>
                        
                        <div class='col-12 form-group'>
                            <label class='form-label register_text-font' for='google_link'>Google Map Link</label>
                            <input type='text' class='form-control register_text-font' id='google_link' placeholder='Enter the map link  (Optional)' name="mapLink">
                        </div>
                        
                    </div>

                    <div class='form-check mt-3 mb-4 d-flex align-items-start'>
                        <input class='form-check-input me-2' type='checkbox' value='' id='terms-check' required name="termAccept">
                        <label class='form-check-label register_text-font small' for='terms-check'>
                            I have read and agree to the Website's Terms & Conditions and Privacy Policy.
                        </label>
                    </div>

                    <div class='d-grid gap-2'>
                        <button type='submit' class='btn btn-lg register-btn'>Register</button>
                    </div>

                    <p class='text-center mt-3 register_text-font'>
                        Already a member? <a href='./login.php' class='register_login-link text-decoration-none'>Login</a>
                    </p>
                </form>
        </div>
        
    </div>
    
    <script src='https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js' integrity='sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL' crossorigin='anonymous'></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', () => {

            // City data parsed from your JSON files
            const myanmarCities = [
                {'name_en': 'Yangon'}, {'name_en': 'Mandalay'}, {'name_en': 'Nay Pyi Taw'}, {'name_en': 'Mawlamyine'}, {'name_en': 'Bago'},
                {'name_en': 'Pathein'}, {'name_en': 'Monywa'}, {'name_en': 'Meiktila'}, {'name_en': 'Taunggyi'}, {'name_en': 'Myitkyina'},
                {'name_en': 'Lashio'}, {'name_en': 'Sittwe'}, {'name_en': 'Pyay'}, {'name_en': 'Hinthada'}, {'name_en': 'Magway'},
                {'name_en': 'Myeik'}, {'name_en': 'Taungoo'}, {'name_en': 'Myingyan'}, {'name_en': 'Dawei'}, {'name_en': 'Pakokku'},
                {'name_en': 'Pyin Oo Lwin'}, {'name_en': 'Hpa-An'}, {'name_en': 'Kyaukse'}, {'name_en': 'Shwebo'}, {'name_en': 'Sagaing'},
                {'name_en': 'Tachileik'}, {'name_en': 'Hakha'}, {'name_en': 'Loikaw'}, {'name_en': 'Kengtung'}, {'name_en': 'Thanlyin'},
                {'name_en': 'Twantay'}, {'name_en': 'Kyauktan'}, {'name_en': 'Bogale'}, {'name_en': 'Pyapon'}, {'name_en': 'Kyaiklat'},
                {'name_en': 'Maubin'}, {'name_en': 'Nyaungdon'}, {'name_en': 'Dedaye'}, {'name_en': 'Kyaukpyu'}, {'name_en': 'Thandwe'},
                {'name_en': 'Toungup'}, {'name_en': 'Gwa'}, {'name_en': 'Manaung'}, {'name_en': 'Kyeintali'}, {'name_en': 'Minbya'},
                {'name_en': 'Mrauk-U'}, {'name_en': 'Pauktaw'}, {'name_en': 'Myebon'}, {'name_en': 'Ann'}, {'name_en': 'Buthidaung'},
                {'name_en': 'Maungdaw'}, {'name_en': 'Kyauktaw'}, {'name_en': 'Ponnagyun'}, {'name_en': 'Rathedaung'}, {'name_en': 'Kawthaung'},
                {'name_en': 'Bokpyin'}, {'name_en': 'Yebyu'}, {'name_en': 'Launglon'}, {'name_en': 'Thayetchaung'}, {'name_en': 'Tanintharyi'},
                {'name_en': 'Kyunsu'}, {'name_en': 'Myitta'}, {'name_en': 'Kawkareik'}, {'name_en': 'Myawaddy'}, {'name_en': 'Kyeikdon'},
                {'name_en': 'Kyeikmaraw'}, {'name_en': 'Hlaingbwe'} , {'name_en' : 'Other'}
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

            // Populate country dropdown
            const countries = ['Myanmar', 'Thailand'];
            countries.forEach(c => {
                const opt = document.createElement('option');
                opt.value = c;
                opt.textContent = c;
                countrySelect.appendChild(opt);
            });

            // Function to handle city population based on selected country
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

            // On country change, load corresponding cities
            countrySelect.addEventListener('change', populateCities);

            // Auto-fill complete address when other address fields change
            const addressFields = ['street', 'township', 'state', 'postal', 'country', 'city'];
            
            // Function to combine address parts
            function updateCompleteAddress() {
                const street = document.getElementById('street').value;
                const township = document.getElementById('township').value;
                const state = document.getElementById('state').value;
                const postal = document.getElementById('postal').value;
                const country = document.getElementById('country').value;
                const city = document.getElementById('city').value;
                
                let parts = [];
                
                // Add parts in the desired order
                if (street) parts.push(street);
                if (township) parts.push(township);
                if (state) parts.push(state);
                if (city) parts.push(city);
                if (country) parts.push(country);
                
                let completeAddress = parts.join(', ');
                
                // Append postal code with a space if it exists
                if (postal) {
                    completeAddress += (completeAddress ? ' ' : '') + postal;
                }
                
                document.getElementById('complete_address').value = completeAddress;
            }

            // Attach event listeners to address fields
            addressFields.forEach(field => {
                const element = document.getElementById(field);
                if (element) {
                    // 'change' for selects, 'input' for text fields
                    element.addEventListener('change', updateCompleteAddress);
                    if (element.tagName.toLowerCase() !== 'select') {
                        element.addEventListener('input', updateCompleteAddress);
                    }
                }
            });

            // Optional: Form submission handler
            document.getElementById('signup-form').addEventListener('submit', function(e) {
                // e.preventDefault(); 
                console.log('Form submitted!');
            });
        });
    </script>

</body>
</html>
<?php
    include '../layout/footer.php';
?>