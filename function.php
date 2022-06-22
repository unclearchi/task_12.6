<?php
//исходный массив
$example_persons_array = [
    [
        'fullname' => 'Иванов Иван Иванович',
        'job' => 'tester',
    ],
    [
        'fullname' => 'Степанова Наталья Степановна',
        'job' => 'frontend-developer',
    ],
    [
        'fullname' => 'Пащенко Владимир Александрович',
        'job' => 'analyst',
    ],
    [
        'fullname' => 'Громов Александр Иванович',
        'job' => 'fullstack-developer',
    ],
    [
        'fullname' => 'Славин Семён Сергеевич',
        'job' => 'analyst',
    ],
    [
        'fullname' => 'Цой Владимир Антонович',
        'job' => 'frontend-developer',
    ],
    [
        'fullname' => 'Быстрая Юлия Сергеевна',
        'job' => 'PR-manager',
    ],
    [
        'fullname' => 'Шматко Антонина Сергеевна',
        'job' => 'HR-manager',
    ],
    [
        'fullname' => 'аль-Хорезми Мухаммад ибн-Муса',
        'job' => 'analyst',
    ],
    [
        'fullname' => 'Бардо Жаклин Фёдоровна',
        'job' => 'android-developer',
    ],
    [
        'fullname' => 'Шварцнегер Арнольд Густавович',
        'job' => 'babysitter',
    ],
];

//Выбираем ФИО из массива случайным образом
$personID = $example_persons_array[random_int(0, count($example_persons_array) - 1)]['fullname'];

//Функция для вывода ФИО в новый массив
function getPartsFromFullName ($personID) {
    
    $arrayPersonFullName = explode (" ", $personID);
    global $fullname;
    $fullname = ['surname' => $arrayPersonFullName[0], 'name' => $arrayPersonFullName[1], 'partname' => $arrayPersonFullName[2]];
    return $fullname;
}

//Функция для ввода ФИО одной строкой 
    function getFullnameFromParts ($surname, $name, $partname) {
        global $person;
        $person = $surname . ' ' . $name . ' ' . $partname;
        return $person;
}

//Функция для сокращения ФИО
function getshortname ($personID) {
    $x = getPartsFromFullName ($personID);
    global $reduction;
    $reduction = $x['name'] . " " . mb_substr ($x['surname'], 0, 1) . ".";
    return $reduction;
}

//функция для определения пола
function getGenderFromName ($fullname) {
    $genderCount = 0;
    $surname = $fullname['surname'];
    $name = $fullname['name'];
    $partname = $fullname['partname'];
    if (mb_substr($surname, -2) == 'ва') {
        $genderCount--;
    }
    if (mb_substr($name, -1) == 'а') {
        $genderCount--;
    }
    if (mb_substr($partname, -3) == 'вна') {
    $genderCount--;
    }
    if (mb_substr($surname, -1) == 'в') {
    $genderCount++;
    }
    if (mb_substr($name, -1) == 'Й' || mb_substr($name, -1) == 'н') {
    $genderCount++;
    }
    if (mb_substr($partname, -2) == 'ич') {
    $genderCount++;
    }
    global $genderID
    $genderID = $genderCount <=> 0;
    return $genderID;
}

//функция подсчета процента женщин и мужчин в массиве\
function getGenderDescription ($example_persons_array) {
    global $genderMessage;
    $person_array_lenght = count($example_persons_array);
    $countMale = 0;
    $countFemale = 0;
    for ($i = 0; $i < $person_array_lenght; $i++) {
        $p = $example_persons_array[$i]['fullname'];
        
        if (getGenderFromName (getPartsFromFullName ($p)) == 1) {
            $countMale++;
        }
        elseif (getGenderFromName (getPartsFromFullName ($p)) == -1) {
            $countFemale++;
        }
    }

    $male = round ($countMale/$person_array_lenght*100, 1, PHP_ROUND_HALF_UP);
    $female = round ($countFemale/$person_array_lenght*100, 1, PHP_ROUND_HALF_UP);
    $notfound = 100-($male+$female);
    $genderMessage = <<<TEXT
    Мужчины - $male %
    Женщины - $female %
    Не удалось определить - $notfound %
    TEXT;
    return $genderMessage;

}

//функция подбора идеальной пары
function getPerfectPartner ($surname, $name, $partname, $example_persons_array) {
    $surname = mb_convert_case ($surname, MB_CASE_TITLE, "UTF-8");
    $name = mb_convert_case ($name, MB_CASE_TITLE, "UTF-8");
    $partname = mb_convert_case ($partname, MB_CASE_TITLE, "UTF-8");
    global $compatibilityMessage;
    $person1String = getFullnameFromParts ($surname, $name, $partname);
    $person1 = getGenderFromName (getPartsFromFullName (getFullnameFromParts ($surname, $name, $partname)));

    if ($person1 != 0) {
        do {
            $person2String = $example_persons_array[random_int(0, count($example_persons_array) - 1)]['fullname'];
            $person2 = getGenderFromName(getPartsFromFullName($person2String));
        }
        while ($person1 == $person2 || $person2 == 0);

        $person1ShortName = getShortName ($person1String);
        $person2ShortName = getShortName ($person2String);
        $randomInt = round (random_int(5000, 10000)/100, 2, PHP_ROUND_HALF_UP);

        $compatibilityMessage = <<<TEXT

        $person1ShortName и $person2ShortName = 
        &#128151 Идеально на $randomInt % &#128151
        TEXT;
        }
        else {
            $compatibilityMessage = <<<TEXT

            Вы необычный человек! &#127801
            Вам трудно подобрать пару...
            TEXT;
        }
        return $compatibilityMessage;
}