import { Component, input } from '@angular/core';

export type IconName =
  | 'bell'
  | 'book'
  | 'calendar'
  | 'chart'
  | 'check'
  | 'groups'
  | 'logout'
  | 'plus'
  | 'school'
  | 'search'
  | 'students'
  | 'teachers';

@Component({
  selector: 'app-ui-icon',
  templateUrl: './ui-icon.html',
  styleUrl: './ui-icon.scss',
})
export class UiIcon {
  readonly name = input.required<IconName>();
  readonly size = input(20);
}
