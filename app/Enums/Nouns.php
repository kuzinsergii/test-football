<?php

namespace App\Enums;

enum Nouns: string
{
    case Lions = 'Lions';
    case Dragons = 'Dragons';
    case Wolves = 'Wolves';
    case Sharks = 'Sharks';
    case Tigers = 'Tigers';
    case Eagles = 'Eagles';
    case Bears = 'Bears';
    case Panthers = 'Panthers';
    case Pirates = 'Pirates';
    case Wizards = 'Wizards';
    case Ghosts = 'Ghosts';
    case Ninjas = 'Ninjas';
    case Aliens = 'Aliens';
    case Giants = 'Giants';
    case Vikings = 'Vikings';
    case Unicorns = 'Unicorns';
    case Owls = 'Owls';
    case Ravens = 'Ravens';
    case Cobras = 'Cobras';
    case Golems = 'Golems';
    case Cyclones = 'Cyclones';
    case Krakens = 'Krakens';
    case Phoenixes = 'Phoenixes';
    case Spiders = 'Spiders';
    case Goblins = 'Goblins';
    case Knights = 'Knights';
    case Warlocks = 'Warlocks';
    case Banshees = 'Banshees';
    case Demons = 'Demons';
    case Yetis = 'Yetis';
    case Badgers = 'Badgers';
    case Pythons = 'Pythons';
    case Coyotes = 'Coyotes';
    case Hedgehogs = 'Hedgehogs';
    case Zombies = 'Zombies';
    case Robots = 'Robots';
    case Bats = 'Bats';
    case Foxes = 'Foxes';
    case Hippos = 'Hippos';
    case Crocodiles = 'Crocodiles';
    case Hamsters = 'Hamsters';
    case Toucans = 'Toucans';
    case Platypuses = 'Platypuses';
    case Leopards = 'Leopards';
    case Bumblebees = 'Bumblebees';
    case Llamas = 'Llamas';
    case Pandas = 'Pandas';
    case Mermaids = 'Mermaids';
    case Squirrels = 'Squirrels';
    case Monkeys = 'Monkeys';

    public static function getRandomValue(): string
    {
        return self::cases()[array_rand(self::cases())]->value;
    }
}
