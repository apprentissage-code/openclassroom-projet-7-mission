<?php

namespace App\Enum;

enum TaskStatus: string
{
  case TODO = 'To Do';
  case DOING = 'Doing';
  case DONE = 'Done';

  public function getLabel(): string
  {
    return match ($this) {
      self::TODO => 'To Do',
      self::DOING => 'Doing',
      self::DONE => 'Done',
    };
  }
}
