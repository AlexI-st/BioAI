   M S B A M L   `   `   `  ���� H  DBioAI_Desktop, Version=1.0.0.0, Culture=neutral, PublicKeyToken=null-clr-namespace:BioAI_DesktopBioAI_Desktop      BioAI_Desktop.MainWindow   R NWindowsBase, Version=4.0.0.0, Culture=neutral, PublicKeyToken=31bf3856ad364e35\ XPresentationFramework, Version=4.0.0.0, Culture=neutral, PublicKeyToken=31bf3856ad364e35W SPresentationCore, Version=4.0.0.0, Culture=neutral, PublicKeyToken=31bf3856ad364e35D 9http://schemas.microsoft.com/winfx/2006/xaml/presentation    R NSystem.Xaml, Version=4.0.0.0, Culture=neutral, PublicKeyToken=b77a5c561934e089:x,http://schemas.microsoft.com/winfx/2006/xaml     @d2http://schemas.microsoft.com/expression/blend/2008     Jmc;http://schemas.openxmlformats.org/markup-compatibility/2006     -localclr-namespace:BioAI_Desktop     -     � Title$  Arduino App�� � WindowState$ 	Maximized=� � WindowStartupLocation$ CenterScreen=� � 	Resources    MyButtonStyle �� 
TargetType �� Property �� Value �� 
TargetType��%c   &	        �� " ��.���  � �� �� " ��.R�� #��z��.+��� $��Center=�$��Center=�.��� .+��� -    ��Name$ ComStackPanel��.+��� ���#4B4E6D	 �� CornerRadius$	 20��$��140, 0q�
 �� Padding$
 300, 100q�._��� .+��� $	 30��._��� -    ��Name$ 	ScrollCom��.���� -    ��Name$ ComsList������ .R��� 
���  ��$��36(�$��Center=����� �� -   $	 20��$��0, 40q����#8CCCBE._��� -    ��Name$ ComChooseButton��
������ 
������ $��Выбрать��
�������$��24(�#��[  �� -   $ CubeStackPanel��$y�	Collapsed=�.+��� ���#4B4E6D$	 20��$��40q�$	
 120q�._��� .+��� -   $ 
ScrollCube��.���� -	   $ 	CubesList������ .R��� 
���  ��$��36(�$��Center=����� �� -
   $ CubeChooseButton��$��Выбрать��� -    �Name$ MainGrid��$y�	Collapsed=� � ColumnDefinitions �� ��  � RowDefinitions �� �� .+��� $��2�����#4B4E6D$��20q�$	 10��._��  �� �� �� �� �� �� �� �� .+��� -    ��Name$ lblPortData��$��0��$'��!Данные о растении:��$��36(�
�������$��Center=�$��0, 15q��� -   $ Temperature��$��1��$��Stretch=�$��Температура:��$��28(�
�������$��Center=��� -   $ Humidity��$��2��$��Stretch=�$(��"Влажность воздуха:��$��28(�
�������$��Center=��� -   $ SoilMoisture��$��3��$��Stretch=�$$��Влажность почвы:��$��28(�
�������$��Center=��� -   $ Lighting��$��4��$��Stretch=�$9��3Процент внешнего освещения:��$��28(�
�������$��Center=��� -   $ LastWateration��$��5��$��Stretch=�$$��Последний полив:��$��28(�
�������$��Center=��� -   $ pH��$��6��$��Stretch=�$	��pH:��$��28(�
�������$��Center=��� -   $ LifeExpectancy��$��7��$��Stretch=�$��Посажено:��$��28(�
�������$��Center=�� $��1��$��10q� �� ��  �� �� .+��� $��0��$��0�����#4B4E6D$	 10��$��30, 5q�$
 10q�._��� #��[  
������ .���� $ ��\Resuorces\Images\lamp.png���� $��0��$��1�����#4B4E6D$	 10��$��30, 5q�$
 10q�._��� #��[  
������ .���� $&�� \Resuorces\Images\water_plus.png���� $��1��$��0�����#4B4E6D$	 10��$��30, 5q�$
 10q�._��� #��[  
������ .���� $ ��\Resuorces\Images\wind.png���� $��1��$��1�����#4B4E6D$	 10��$��30, 5q�$
 10q�._��� #��[  
������ .���� $'��!\Resuorces\Images\thermometer.png���� $��1��$��1��$��Center=�.+��� ���#8CCCBE$	 10��$��10, 5q�$
 0, 80q�._��� 
�������$��48(�$%��Журнал состояний��#��[  
������ �� ���#8CCCBE$	 10��$��10, 5q�$
 0, 80q�._��� 
�������$��48(�$��Статистика��#��[  
������ 