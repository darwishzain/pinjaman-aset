import { Image } from 'expo-image';
import { Platform, StyleSheet,ScrollView,View } from 'react-native';

import { ThemedText } from '@/components/themed-text';
import { ThemedView } from '@/components/themed-view';
import { IconSymbol } from '@/components/ui/icon-symbol';
import { Fonts } from '@/constants/theme';

export default function ItemScreen() {
  return (
    <ScrollView
      style={{ flex: 1, backgroundColor: 'white' }}>
      <View style={{ padding: 20 }}>
        {
          <ThemedText>Hello</ThemedText>
        }
      </View>
    </ScrollView>
  );
}

const styles = StyleSheet.create({
  
});
