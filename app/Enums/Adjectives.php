<?php

namespace App\Enums;

enum Adjectives: string
{
    case Red = 'Red';
    case Blue = 'Blue';
    case Green = 'Green';
    case Black = 'Black';
    case White = 'White';
    case Golden = 'Golden';
    case Furious = 'Furious';
    case Mysterious = 'Mysterious';
    case Wild = 'Wild';
    case Brave = 'Brave';
    case Invisible = 'Invisible';
    case Angry = 'Angry';
    case Shiny = 'Shiny';
    case Mystic = 'Mystic';
    case Noisy = 'Noisy';
    case Lazy = 'Lazy';
    case Spicy = 'Spicy';
    case Tiny = 'Tiny';
    case Giant = 'Giant';
    case Swift = 'Swift';
    case Cunning = 'Cunning';
    case Silly = 'Silly';
    case Majestic = 'Majestic';
    case Dark = 'Dark';
    case Bright = 'Bright';
    case Fearless = 'Fearless';
    case Electric = 'Electric';
    case Cosmic = 'Cosmic';
    case Sneaky = 'Sneaky';
    case Noble = 'Noble';
    case Chaotic = 'Chaotic';
    case Grumpy = 'Grumpy';
    case Frosty = 'Frosty';
    case Burning = 'Burning';
    case Silver = 'Silver';
    case Reckless = 'Reckless';
    case Hungry = 'Hungry';
    case Witty = 'Witty';
    case Phantom = 'Phantom';
    case Crazy = 'Crazy';
    case Gentle = 'Gentle';
    case Loyal = 'Loyal';
    case Rusty = 'Rusty';
    case Cursed = 'Cursed';
    case Vicious = 'Vicious';
    case Playful = 'Playful';
    case Gloomy = 'Gloomy';
    case Jolly = 'Jolly';
    case Stubborn = 'Stubborn';
    case Legendary = 'Legendary';

    public static function getRandomValue(): string
    {
        return self::cases()[array_rand(self::cases())]->value;
    }
}
